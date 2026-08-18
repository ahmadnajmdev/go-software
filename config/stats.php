<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Company figures
    |--------------------------------------------------------------------------
    |
    | Every number the site claims about itself, in one place.
    |
    | ALL NULL ON PURPOSE. The site previously claimed "300+ projects
    | delivered", "180+ happy clients", "15+ years in software" and a "98%
    | satisfaction rate". None of them could be sourced, and two of them
    | contradicted each other in public — 300 projects sat two screens from
    | 15 years. A prospect who notices discounts everything else on the page.
    |
    | So nothing is claimed. A figure set to null is not rendered anywhere, and
    | the stats band disappears entirely when none of them is set.
    |
    | Set a real, defensible number here and it comes straight back — the band
    | renders whatever subset is filled in, and the About photo badge follows
    | years_in_software. See BLOCKED.md.
    |
    */

    'projects_delivered' => null,
    'happy_clients' => null,
    'years_in_software' => null,
    'satisfaction_rate' => null,

    /*
    |--------------------------------------------------------------------------
    | Award badge
    |--------------------------------------------------------------------------
    |
    | The "Top-rated Software agency 2025" badge claimed an award without ever
    | naming who gave it. An unsourced award is worse than no award, so the
    | badge renders ONLY when `awarded_by` names a real organisation.
    |
    */

    'award' => [
        'awarded_by' => null,
        'year' => 2025,
    ],

];
