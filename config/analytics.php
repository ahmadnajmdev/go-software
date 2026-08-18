<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Tag Manager
    |--------------------------------------------------------------------------
    |
    | The container ID (GTM-XXXXXXX). GA4, Meta Pixel and Microsoft Clarity are
    | loaded through the container rather than hard-coded into the site, so this
    | is the only third-party ID the codebase needs to know about.
    |
    | Leave it empty and no tracking code is emitted at all.
    |
    */

    'gtm_id' => env('GTM_ID'),

];
