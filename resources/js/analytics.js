/**
 * Conversion tracking.
 *
 * Pushes named events onto the GTM dataLayer. GA4, Meta Pixel and Clarity are
 * wired to these events inside the container, so this file is the only place
 * the site describes *what happened* — never *who to report it to*.
 *
 * Vanilla, no dependencies. Nothing fires when Do Not Track is on.
 *
 * Most tracking is declarative — put the attributes on the element:
 *
 *   <a data-gs-track="cta_click" data-gs-label="Get an estimate" data-gs-location="hero">
 *
 * Every `data-gs-*` attribute other than `data-gs-track` becomes an event
 * parameter, so new events need markup only, not changes here. Phone, email and
 * WhatsApp links are recognised from their href and need no attributes at all.
 */

/** Do Not Track is honoured even when no container was emitted. */
function trackingAllowed() {
    if (window.gsNoTrack) return false;

    const dnt = window.doNotTrack || navigator.doNotTrack || navigator.msDoNotTrack;

    return !(dnt === '1' || dnt === 'yes' || dnt === 1 || dnt === true);
}

/** Sent with every event so each one can be segmented by page and language. */
function baseParams() {
    return {
        page: window.location.pathname,
        language: document.documentElement.lang || 'en',
    };
}

/**
 * Push one event. Safe to call before GTM loads — the container replays
 * whatever is already sitting on the dataLayer when it initialises.
 */
export function gsTrack(event, params = {}) {
    if (!trackingAllowed()) return;

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event, ...baseParams(), ...params });
}

/** `data-gs-label="x"` → `{ label: 'x' }`. */
function paramsFrom(el) {
    const params = {};

    for (const { name, value } of el.attributes) {
        if (!name.startsWith('data-gs-') || name === 'data-gs-track') continue;
        params[name.slice(8).replace(/-([a-z])/g, (_, c) => c.toUpperCase())] = value;
    }

    return params;
}

const SELECTOR = [
    '[data-gs-track]',
    'a[href^="tel:"]',
    'a[href^="mailto:"]',
    'a[href*="wa.me/"]',
].join(',');

document.addEventListener('click', (event) => {
    const el = event.target.closest(SELECTOR);
    if (!el) return;

    // An explicit data-gs-track always wins, so a WhatsApp button can still be
    // reported as something more specific than the href implies.
    if (el.dataset.gsTrack) {
        gsTrack(el.dataset.gsTrack, paramsFrom(el));
        return;
    }

    const href = el.getAttribute('href') || '';

    if (href.startsWith('tel:')) gsTrack('phone_click');
    else if (href.startsWith('mailto:')) gsTrack('email_click', { page: window.location.pathname });
    else if (href.includes('wa.me/')) gsTrack('whatsapp_click', paramsFrom(el));
});

// form_start: the first time a visitor touches any field of a form. One per
// form per page view — re-focusing a field is not a new start.
document.addEventListener('focusin', (event) => {
    const form = event.target.closest('form[data-gs-form]');
    if (!form || form.dataset.gsStarted) return;

    form.dataset.gsStarted = '1';
    gsTrack('form_start', { form: form.dataset.gsForm });
}, true);

// faq_open: `toggle` does not bubble, so this listens in the capture phase.
document.addEventListener('toggle', (event) => {
    const el = event.target;

    if (el.tagName === 'DETAILS' && el.open && el.dataset.gsQuestion) {
        gsTrack('faq_open', { question: el.dataset.gsQuestion });
    }
}, true);

// Available to Blade and to the Alpine components that own their own submit.
window.gsTrack = gsTrack;
