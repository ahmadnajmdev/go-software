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
     * The animated counters — only the figures that have actually been
     * supplied. Every one is null by default: the site used to claim numbers
     * nobody could source, and two of them contradicted each other.
     *
     * @return array<int, array{count: int, suffix: string, label: string}>
     */
    public static function forCounters(): array
    {
        $figures = [
            ['key' => 'projects_delivered', 'suffix' => '+', 'label' => 'st1'],
            ['key' => 'happy_clients', 'suffix' => '+', 'label' => 'st2'],
            ['key' => 'years_in_software', 'suffix' => '+', 'label' => 'st3'],
            ['key' => 'satisfaction_rate', 'suffix' => '%', 'label' => 'st4'],
        ];

        $counters = [];

        foreach ($figures as $figure) {
            $value = config('stats.'.$figure['key']);

            if (is_numeric($value)) {
                $counters[] = [
                    'count' => (int) $value,
                    'suffix' => $figure['suffix'],
                    'label' => $figure['label'],
                ];
            }
        }

        return $counters;
    }

    /** Whether any figure is claimed at all. */
    public static function hasFigures(): bool
    {
        return self::forCounters() !== [];
    }

    /** The years-in-business badge on the About photo, or null. */
    public static function years(): ?int
    {
        $years = config('stats.years_in_software');

        return is_numeric($years) ? (int) $years : null;
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
