import Alpine from 'alpinejs';
import { gsTrack } from './analytics';

// Hero carousel: 2 slides, auto-advance every 7s (paused in inline-edit mode).
Alpine.data('heroCarousel', () => ({
    slide: 0,
    timer: null,
    init() {
        this.timer = setInterval(() => {
            if (document.body.dataset.edit !== 'true') this.toggle();
        }, 7000);
    },
    toggle() {
        this.slide = this.slide === 0 ? 1 : 0;
    },
    trackTransform() {
        const rtl = document.body.dir === 'rtl';
        const shift = rtl ? '50%' : '-50%';
        return this.slide === 1 ? `translateX(${shift})` : 'translateX(0)';
    },
    label() {
        return `0${this.slide + 1} / 02`;
    },
    destroy() {
        clearInterval(this.timer);
    },
}));

// Contact form: fetch-submit and swap to the success panel, like the design.
Alpine.data('contactForm', (serverErrors = {}, sent = false) => ({
    // Seeded from the server so a no-JS submission that came back with errors
    // keeps showing them once Alpine takes over.
    errors: serverErrors,
    submitted: sent,
    sending: false,
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
                headers: { 'Accept': 'application/json' },
                body: data,
            });
            if (response.ok) {
                this.submitted = true;
                // budget and timeline arrive with the multi-step form; until
                // then they are simply absent rather than reported as empty.
                gsTrack('form_submit', {
                    service: data.get('service') || '(none)',
                    ...(data.has('budget') ? { budget: data.get('budget') } : {}),
                    ...(data.has('timeline') ? { timeline: data.get('timeline') } : {}),
                });
                this.errors = {};
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
     * Show each rejected field in place and report one form_error per field,
     * so the field people give up on is visible in the funnel.
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
        this.$nextTick(() => {
            const first = this.$el.querySelector(`[name="${fields[0]}"]`);
            first?.focus({ preventScroll: false });
        });
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
