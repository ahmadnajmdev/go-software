<?php

namespace App\Support;

use App\Models\UiString;
use Illuminate\Support\Facades\Cache;

/**
 * The design defaults for every UI string, and where each one belongs.
 *
 * Shared by the seeder and by migrations. Site copy lives in the database and
 * is admin-editable, so a key added to the codebase does not reach an existing
 * install until something inserts it — and until it does, t() falls back to
 * returning the key name and the raw key renders on the page. syncMissing()
 * is that something.
 */
class UiStringDefaults
{
    /** Sections that were removed from the site; their strings are not seeded. */
    private const RETIRED_GROUPS = ['Pricing', 'Blog', 'Team'];

    private const RETIRED_KEYS = ['navPricing', 'navBlog', 'ftBlog', 'ftTeam'];

    public static function all(): array
    {
        return require database_path('seeders/data/ui_strings.php');
    }

    public static function isRetired(string $key): bool
    {
        return in_array(self::groupFor($key), self::RETIRED_GROUPS, true)
            || in_array($key, self::RETIRED_KEYS, true);
    }

    /**
     * Insert any default that this install has never seen. Existing rows are
     * left exactly as they are, so copy edited in the admin panel survives.
     *
     * @return array<int, string> the keys that were created
     */
    public static function syncMissing(): array
    {
        $existing = UiString::pluck('key')->flip();
        $created = [];

        foreach (self::all() as $key => $value) {
            if ($existing->has($key) || self::isRetired($key)) {
                continue;
            }

            UiString::create(['key' => $key, 'value' => $value, 'group' => self::groupFor($key)]);
            $created[] = $key;
        }

        Cache::forget('gs.strings');

        return $created;
    }

    public static function groupFor(string $key): string
    {
        return match (true) {
            str_starts_with($key, 'nav') || in_array($key, ['getQuote', 'location', 'followUs']) => 'Navigation',
            str_starts_with($key, 'h1') || str_starts_with($key, 'h2') || str_starts_with($key, 'hero')
                || in_array($key, ['projDelivered', 'yrsBadge', 'ofEng', 'ctaEstimate', 'trustedBy']) => 'Hero',
            (bool) preg_match('/^f\d/', $key) => 'Feature strip',
            str_starts_with($key, 'about') || (bool) preg_match('/^ab\d/', $key)
                || in_array($key, ['ceoRole', 'yearsIn']) => 'About',
            str_starts_with($key, 'svc') || in_array($key, ['webDev', 'mgmtSystems', 'learnMore', 'ownCode']) => 'Services',
            str_starts_with($key, 'why') || str_starts_with($key, 'mq')
                || in_array($key, ['topRated', 'agency2025', 'mobileApps']) => 'Why us',
            str_starts_with($key, 'proc') || (bool) preg_match('/^p\d/', $key) => 'Process',
            str_starts_with($key, 'proj') || str_starts_with($key, 'prj') || (bool) preg_match('/^cat\d/', $key)
                || in_array($key, ['allProjects', 'catAll']) => 'Projects',
            str_starts_with($key, 'meta') => 'SEO',
            str_starts_with($key, 'prob') => 'Problem',
            str_starts_with($key, 'ind') => 'Industries',
            (bool) preg_match('/^st\d/', $key) => 'Stats',
            str_starts_with($key, 'team') || (bool) preg_match('/^role\d/', $key) => 'Team',
            str_starts_with($key, 'founder') => 'Founder',
            str_starts_with($key, 'tst') => 'Testimonials',
            str_starts_with($key, 'price') || str_starts_with($key, 'plan') || str_starts_with($key, 'feat')
                || in_array($key, ['monthly', 'annual', 'save20', 'getStarted', 'mostPopular', 'month', 'year']) => 'Pricing',
            str_starts_with($key, 'blog') || $key === 'readMore' => 'Blog',
            str_starts_with($key, 'ct') || str_starts_with($key, 'ph') || str_starts_with($key, 'opt')
                || str_starts_with($key, 'err') || str_starts_with($key, 'wa')
                || in_array($key, ['callUs', 'emailUs', 'visitUs', 'getDirections', 'formTitle', 'formSub',
                    'sendMsg', 'thanksT', 'thanksB']) => 'Contact',
            str_starts_with($key, 'ft')
                || in_array($key, ['copyright', 'privacy', 'terms', 'lastUpdated', 'legalContact',
                    'warrantyLine', 'supportTiersTitle', 'supportResponse']) => 'Footer',
            default => 'Other',
        };
    }
}
