import Alpine from 'alpinejs';
import { gsTrack } from './analytics';

// Contact form: four steps, one question at a time.
//
// Step one is a single tap with no typing — the cheapest first commitment we
// can ask for. Everything degrades to one long form when Alpine is absent,
// because x-show simply does not run and every fieldset stays visible.
Alpine.data('contactForm', (serverErrors = {}, sent = false, service = '') => ({
    total: 4,
    step: 1,
    service,
    errors: serverErrors,
    submitted: sent,
    sending: false,

    init() {
        // Come back from a failed submit and land on the step that broke.
        const first = Object.keys(this.errors)[0];
        if (first) this.step = this.stepOf(first);
    },

    /** Which step owns a given field, so errors are never off-screen. */
    stepOf(field) {
        const map = { service: 1, message: 2, name: 3, phone: 3, email: 3, company: 3 };
        return map[field] ?? this.total;
    },

    title() {
        return [this.t('stepWhat'), this.t('stepAbout'), this.t('stepReach'), this.t('stepQuote')][this.step - 1];
    },

    stepLabel() {
        return this.t('fStep').replace(':n', this.step).replace(':total', this.total);
    },

    /** Step 2's placeholder is chosen by the step 1 answer — the cheapest
     *  qualification tool available, and it costs the visitor nothing. */
    prompt() {
        const key = {
            website: 'phWebsite', mobile: 'phMobileApp', system: 'phMgmt',
            pos: 'phPos', ecommerce: 'phEcom', other: 'phOther',
        }[this.service];
        return key ? this.t(key) : this.t('phMsg');
    },

    /** Strings are rendered into the page by Blade; this reads them back. */
    t(key) {
        return (window.gsStrings || {})[key] ?? '';
    },

    advance() {
        if (this.step === 1 && !this.service) return;
        if (this.step >= this.total) return;

        window.gsTrack?.('form_step_complete', { step: this.step, service: this.service || '(none)' });
        this.step++;
        this.focusStep();
    },

    back() {
        if (this.step > 1) this.step--;
    },

    focusStep() {
        this.$nextTick(() => {
            this.$el.querySelector(`fieldset:not([style*="display: none"]) textarea, fieldset:not([style*="display: none"]) input:not([type=hidden]):not([type=radio])`)?.focus();
        });
    },

    hasErrors() {
        return Object.keys(this.errors).length > 0;
    },

    async submit(event) {
        if (this.sending) return;
        this.sending = true;
        const form = event.target;
        const data = new FormData(form);
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { Accept: 'application/json' },
                body: data,
            });
            if (response.ok) {
                this.submitted = true;
                this.errors = {};
                gsTrack('form_step_complete', { step: this.total, service: data.get('service') || '(none)' });
                gsTrack('form_submit', {
                    service: data.get('service') || '(none)',
                    budget: data.get('budget') || '(not given)',
                    timeline: data.get('timeline') || '(not given)',
                });
            } else if (response.status === 422) {
                await this.showErrors(response);
            } else {
                form.submit(); // 419/429/5xx — let the server render the page
            }
        } catch {
            form.submit(); // offline or blocked: fall back to a full POST
        } finally {
            this.sending = false;
        }
    },

    /**
     * Show each rejected field in place, on its own step, and report one
     * form_error per field so the field people give up on is visible.
     */
    async showErrors(response) {
        try {
            const body = await response.json();
            this.errors = body.errors || {};
        } catch {
            this.errors = {};
        }
        const fields = Object.keys(this.errors);
        if (!fields.length) {
            gsTrack('form_error', { field: '(unknown)' });
            return;
        }
        fields.forEach((field) => gsTrack('form_error', { field }));
        this.step = this.stepOf(fields[0]);
        this.$nextTick(() => this.$el.querySelector(`[name="${fields[0]}"]`)?.focus({ preventScroll: false }));
    },
}));

window.Alpine = Alpine;
Alpine.start();

// Stats counters (ported from the design: threshold .4, ~45 rAF steps, run once).
const counters = document.querySelectorAll('.gs-count');
if (counters.length && 'IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting || entry.target.dataset.done) return;
            entry.target.dataset.done = '1';
            const el = entry.target;
            const target = parseInt(el.dataset.count, 10) || 0;
            const suffix = el.dataset.suffix || '';
            const steps = 45;
            let step = 0;
            const tick = () => {
                step++;
                el.textContent = Math.round((target * step) / steps) + suffix;
                if (step < steps) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        });
    }, { threshold: 0.4 });
    counters.forEach((el) => io.observe(el));
}

// Sticky mobile action bar: revealed once the visitor is 20% down the page,
// so it never covers the hero on arrival. Passive listener, rAF-throttled —
// this runs on mid-range Androids over 4G.
const stickyBar = document.getElementById('gs-sticky');
if (stickyBar) {
    let ticking = false;
    const update = () => {
        ticking = false;
        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        const past = scrollable > 0 && window.scrollY / scrollable >= 0.2;
        stickyBar.classList.toggle('is-on', past);
    };
    const onScroll = () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(update);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
}
