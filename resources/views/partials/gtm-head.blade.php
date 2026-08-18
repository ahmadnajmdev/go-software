{{-- Google Tag Manager (head). Emitted only when a container ID is configured,
     and only for visitors who have not switched Do Not Track on: the container
     is never injected for them, so no vendor tag inside it can fire either. --}}
@php
    // Guard the interpolation: a malformed .env value must not be able to
    // break out of the JS string literal below.
    $gtmId = config('analytics.gtm_id');
    $gtmId = is_string($gtmId) && preg_match('/^GTM-[A-Z0-9]{4,12}$/', $gtmId) ? $gtmId : null;
@endphp
@if ($gtmId)
<script>
(function (w, d, s, id) {
    var dnt = w.doNotTrack || w.navigator.doNotTrack || w.navigator.msDoNotTrack;
    if (dnt === '1' || dnt === 'yes' || dnt === 1 || dnt === true) {
        w.gsNoTrack = true;
        return;
    }
    w.dataLayer = w.dataLayer || [];
    w.dataLayer.push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
    var f = d.getElementsByTagName(s)[0], j = d.createElement(s);
    j.async = true;
    j.src = 'https://www.googletagmanager.com/gtm.js?id=' + id;
    f.parentNode.insertBefore(j, f);
})(window, document, 'script', @json($gtmId));
</script>
@endif
