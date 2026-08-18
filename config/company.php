<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Legal identity
    |--------------------------------------------------------------------------
    |
    | A registered company name and number in the footer is the cheapest trust
    | signal there is, and its absence is conspicuous to anyone comparing
    | suppliers. Both are null until supplied — the footer line does not render
    | at all rather than showing a placeholder. See BLOCKED.md.
    |
    */

    'legal_name' => null,
    'registration_number' => null,

    /*
    |--------------------------------------------------------------------------
    | Post-launch warranty
    |--------------------------------------------------------------------------
    |
    | Number of days of free bug fixes after launch. No local competitor states
    | one, so it is a real differentiator — but it is a commitment to make, not
    | one to assume. Null means the statement is not shown anywhere.
    |
    */

    'warranty_days' => null,

    /*
    |--------------------------------------------------------------------------
    | Support tiers
    |--------------------------------------------------------------------------
    |
    | Published response times for the support page. Each entry:
    |
    |     ['name' => 'Standard', 'response' => '1 working day',
    |      'price' => '$X / month', 'includes' => ['…', '…']]
    |
    | Empty means the tier table does not render.
    |
    */

    'support_tiers' => [],

];
