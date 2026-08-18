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
 * Batched delivery to our own collector.
 *
 * One request per event turned out to be the wrong shape: a handful of clicks
 * in the same second put several writes in flight at once, and on a
 * database-backed cache the rate limiter's own counter update is what locks,
 * which fails the request before it reaches the handler. Events are queued and
 * sent together instead — far fewer requests, one insert, one limiter hit.
 */
const QUEUE = [];
const BATCH_LIMIT = 20;
let flushTimer = null;

function endpoint() {
    return document.querySelector('meta[name="gs-collect"]')?.content;
}

function flush() {
    clearTimeout(flushTimer);
    flushTimer = null;

    const url = endpoint();
    if (!url || !QUEUE.length) return;

    const events = QUEUE.splice(0, BATCH_LIMIT);
    const body = JSON.stringify({ events });

    try {
        if (navigator.sendBeacon) {
            // Survives the page being navigated away from, which is exactly
            // what a click on an outbound WhatsApp link does.
            const queued = navigator.sendBeacon(url, new Blob([body], { type: 'application/json' }));
            if (queued) return;
        }
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body,
            keepalive: true,
        }).catch(() => {});
    } catch {
        /* analytics must never break the page */
    }
}

function collect(payload) {
    if (!endpoint()) return;

    QUEUE.push(payload);

    if (QUEUE.length >= BATCH_LIMIT) {
        flush();
        return;
    }

    // Short enough that a bounce still reports, long enough to gather a burst.
    clearTimeout(flushTimer);
    flushTimer = setTimeout(flush, 800);
}

// Whatever is still queued when the page goes away must still be sent.
// pagehide covers navigation and the back/forward cache; visibilitychange
// covers a phone being locked or the tab being switched, which on iOS is
// often the only signal that arrives.
addEventListener('pagehide', flush);
addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'hidden') flush();
});

/**
 * Record one event. Goes to the GTM dataLayer (where GA4 and the pixels live)
 * and, batched, to our own collector (which feeds the admin dashboard). Safe to
 * call before GTM loads — the container replays whatever is already on the
 * dataLayer when it initialises.
 */
export function gsTrack(event, params = {}) {
    if (!trackingAllowed()) return;

    const payload = { event, ...baseParams(), ...params };

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push(payload);

    const { event: name, ...rest } = payload;
    collect({ name, ...rest });
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

// One page_view per page, so every other number has a denominator — a
// conversion rate needs to know how many people arrived.
gsTrack('page_view');
