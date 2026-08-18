<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Company figures
    |--------------------------------------------------------------------------
    |
    | Every number the site claims about itself, in one place.
    |
    | ⚠ THESE ARE UNVERIFIED. They are the figures the site was already showing,
    | moved here unchanged — not researched, not confirmed. They contradicted
    | each other in public: "300+ projects delivered" sat two screens from
    | "15+ years in software", which reads as either 20 projects a year for
    | fifteen years, or something nobody counted. A prospect who notices
    | discounts every other claim on the page.
    |
    | Correct them here and every place they appear updates. See BLOCKED.md.
    |
    */

    'projects_delivered' => 300,
    'happy_clients' => 180,
    'years_in_software' => 15,
    'satisfaction_rate' => 98,

    /*
    |--------------------------------------------------------------------------
    | Award badge
    |--------------------------------------------------------------------------
    |
    | The "Top-rated Software agency 2025" badge claimed an award without ever
    | naming who gave it. An unsourced award is worse than no award, so the
    | badge renders ONLY when `awarded_by` names a real organisation.
    |
    | Set it to the awarding body's name to bring the badge back. Leave it null
    | and the badge does not render at all.
    |
    */

    'award' => [
        'awarded_by' => null,
        'year' => 2025,
    ],

];
