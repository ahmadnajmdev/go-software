{{-- Google Tag Manager (noscript). Paired with partials/gtm-head. --}}
@php
    $gtmId = config('analytics.gtm_id');
    $gtmId = is_string($gtmId) && preg_match('/^GTM-[A-Z0-9]{4,12}$/', $gtmId) ? $gtmId : null;
@endphp
@if ($gtmId)
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
