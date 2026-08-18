<?php

namespace App\Support;

/**
 * The company figures shown on the site, from config/stats.php.
 *
 * They used to be spread between a settings row, a hardcoded number in the
 * About section and a badge in Why Us, which is how the site ended up claiming
 * 300 projects and 15 years within two screens of each other.
 */
class Stats
{
    /**
     * The four animated counters, paired with their labels (st1..st4).
     *
     * @return array<int, array{count: int, suffix: string}>
     */
    public static function forCounters(): array
    {
        return [
            ['count' => (int) config('stats.projects_delivered'), 'suffix' => '+'],
            ['count' => (int) config('stats.happy_clients'), 'suffix' => '+'],
            ['count' => (int) config('stats.years_in_software'), 'suffix' => '+'],
            ['count' => (int) config('stats.satisfaction_rate'), 'suffix' => '%'],
        ];
    }

    /** An award is only shown once someone has said who gave it. */
    public static function hasAward(): bool
    {
        return filled(config('stats.award.awarded_by'));
    }

    public static function awardedBy(): string
    {
        return (string) config('stats.award.awarded_by');
    }

    public static function awardYear(): string
    {
        return (string) config('stats.award.year');
    }
}
