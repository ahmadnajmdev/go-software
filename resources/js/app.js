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
Alpine.data('contactForm', () => ({
    submitted: false,
    sending: false,
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
            } else {
                await this.reportErrors(response);
                form.submit(); // fall back to a full POST so validation errors render
            }
        } catch {
            form.submit();
        } finally {
            this.sending = false;
        }
    },
    /** One form_error per rejected field, so the drop-off field is visible. */
    async reportErrors(response) {
        try {
            const body = await response.json();
            const fields = Object.keys(body.errors || {});
            if (!fields.length) throw new Error('no field errors');
            fields.forEach((field) => gsTrack('form_error', { field }));
        } catch {
            gsTrack('form_error', { field: '(unknown)' });
        }
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
