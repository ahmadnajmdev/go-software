<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Social profiles
    |--------------------------------------------------------------------------
    |
    | Which channels the site knows how to render, in display order. The URLs
    | themselves are edited in the admin panel (Settings → Social links) and
    | stored as `social.*` / `founder.*` settings; these arrays only say which
    | networks exist and what to label them.
    |
    | A channel with no URL configured is NOT rendered — no placeholder, no
    | dead icon. Nothing here may be guessed: an unset key means the icon is
    | simply absent until a real profile URL is supplied.
    |
    */

    'networks' => [
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'linkedin' => 'LinkedIn',
        'youtube' => 'YouTube',
        'x' => 'X (Twitter)',
    ],

    // The founder's personal profiles, shown on the founder card.
    'founder_networks' => [
        'linkedin' => 'LinkedIn (Ahmad)',
        'instagram' => 'Instagram (Ahmad)',
    ],

];
