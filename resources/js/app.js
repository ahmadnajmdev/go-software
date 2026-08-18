import Alpine from 'alpinejs';
import { gsTrack } from './analytics';

// Contact form: four steps, one question at a time.
//
// Step one is a single tap with no typing — the cheapest first commitment we
// can ask for. Everything degrades to one long form when Alpine is absent,
// because x-show does not run and every fieldset stays visible.
//
// Each step is checked before it will let you past it. Without that a visitor
// can tap through to the end with nothing filled in and only discover it when
// the server rejects the whole thing — which is exactly the experience a
// stepped form is supposed to prevent.
Alpine.data('contactForm', (serverErrors = {}, sent = false, service = '') => ({
    total: 4,
    step: 1,
    service,
    errors: serverErrors,
    submitted: sent,
    sending: false,

    // Alpine's $el is "the element the expression is on", so inside a method
    // reached from @click on a button it is that button — which contains no
    // fields. Captured here in init(), where $el really is the component root.
    root: null,

    /** Which step owns a field, so an error is never reported off-screen. */
    stepOf(field) {
        return { service: 1, message: 2, name: 3, phone: 3, email: 3, company: 3 }[field] ?? this.total;
    },

    init() {
        this.root = this.$el;

        // We do our own per-step checking and render the messages inline. The
        // browser's own validation would refuse to submit because the earlier
        // steps are display:none by then, and it cannot focus a hidden field
        // to complain about it — the click would do nothing at all. The
        // required attributes stay in the markup for the no-JS path.
        this.$nextTick(() => {
            const form = this.root.querySelector('form');
            if (form) form.noValidate = true;
        });

        // Come back from a failed submit and land on the step that broke.
        const first = Object.keys(this.errors)[0];
        if (first) this.step = this.stepOf(first);
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

    /** The fields belonging to a step, in document order. */
    fieldsOf(step) {
        const fieldset = this.root.querySelectorAll('form fieldset')[step - 1];
        if (!fieldset) return [];

        return [...fieldset.querySelectorAll('input, textarea, select')]
            .filter((el) => el.name && el.type !== 'hidden' && el.type !== 'radio' && !el.disabled);
    },

    /** The same wording the server would use, in the visitor's language. */
    messageFor(field, kind) {
        const key = {
            service: 'errService',
            message: 'errMessage',
            name: 'errName',
            phone: 'errPhone',
            email: kind === 'format' ? 'errEmailValid' : 'errEmail',
        }[field];

        return this.t(key) || this.t('errGeneric');
    },

    /**
     * Check the current step. Errors on other steps are left alone so a
     * server-reported problem elsewhere does not vanish silently.
     */
    validateStep() {
        const errors = { ...this.errors };
        this.fieldsOf(this.step).forEach((el) => delete errors[el.name]);
        if (this.step === 1) delete errors.service;

        if (this.step === 1 && !this.service) {
            errors.service = [this.messageFor('service')];
        }

        this.fieldsOf(this.step).forEach((el) => {
            const value = (el.value || '').trim();

            if (el.required && !value) {
                errors[el.name] = [this.messageFor(el.name)];
                return;
            }

            if (!value) return;

            // A phone needs actual digits. The field starts at "+964", which
            // is not empty but is not a number either — the server counts
            // digits, so the browser has to as well or people are bounced
            // back for something we could have caught here.
            if (el.name === 'phone' && (value.match(/\d/g) || []).length < 6) {
                errors.phone = [this.messageFor('phone')];
                return;
            }

            if (!el.checkValidity()) {
                errors[el.name] = [this.messageFor(el.name, 'format')];
            }
        });

        this.errors = errors;

        return !this.fieldsOf(this.step).some((el) => this.errors[el.name])
            && !(this.step === 1 && this.errors.service);
    },

    /** Clear a field's error as soon as the visitor starts fixing it. */
    clearError(field) {
        if (field && this.errors[field]) {
            const { [field]: _removed, ...rest } = this.errors;
            this.errors = rest;
        }
    },

    hasErrors() {
        return Object.keys(this.errors).length > 0;
    },

    focusFirstInvalid() {
        this.$nextTick(() => {
            const target = this.fieldsOf(this.step).find((el) => this.errors[el.name]);
            (target || this.fieldsOf(this.step)[0])?.focus({ preventScroll: false });
        });
    },

    advance() {
        if (this.step >= this.total) return;

        if (!this.validateStep()) {
            this.focusFirstInvalid();
            return;
        }

        window.gsTrack?.('form_step_complete', { step: this.step, service: this.service || '(none)' });
        this.step++;
        this.$nextTick(() => this.fieldsOf(this.step)[0]?.focus({ preventScroll: true }));
    },

    back() {
        if (this.step > 1) this.step--;
    },

    /** Enter should move to the next step, not submit from halfway through. */
    onEnter(event) {
        if (event.target.tagName === 'TEXTAREA') return;

        if (this.step < this.total) {
            event.preventDefault();
            this.advance();
        }
    },

    async submit(event) {
        if (this.sending) return;

        // Re-check every step, not just the last one — someone can reach the
        // end and then go back and empty a field.
        for (let step = 1; step <= this.total; step++) {
            const at = this.step;
            this.step = step;
            const ok = this.validateStep();
            this.step = at;

            if (!ok) {
                this.step = step;
                this.focusFirstInvalid();
                Object.keys(this.errors).forEach((field) => gsTrack('form_error', { field }));
                return;
            }
        }

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
     * Show each rejected field on its own step, and report one form_error per
     * field so the field people give up on is visible in the funnel.
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
        this.step = Math.min(...fields.map((field) => this.stepOf(field)));
        this.focusFirstInvalid();
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
