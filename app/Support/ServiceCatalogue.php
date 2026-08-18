<?php

namespace App\Support;

use App\Models\Service;

/**
 * Long-form service page copy, from resources/services/catalogue.php.
 *
 * The database row owns the card image, tag and ordering so the admin panel
 * keeps working; the page argument — headline, who it is for, what you get,
 * process, objections — lives in the catalogue, because it has to hold
 * together as one piece rather than be edited a line at a time.
 */
class ServiceCatalogue
{
    public static function all(): array
    {
        static $catalogue;

        return $catalogue ??= require resource_path('services/catalogue.php');
    }

    public static function has(string $slug): bool
    {
        return isset(self::all()[$slug]);
    }

    /** Page copy for a slug in the current locale, falling back to English. */
    public static function page(string $slug, ?string $locale = null): ?array
    {
        if (! $entry = self::all()[$slug] ?? null) {
            return null;
        }

        $locale ??= app()->getLocale();

        return ($entry[$locale] ?? $entry['en']) + [
            'slug' => $slug,
            'tag' => $entry['tag'],
            'whatsapp' => $entry['whatsapp'],
        ];
    }

    /** The outcome-led headline used on the home page service cards. */
    public static function headline(?string $slug, ?string $locale = null): ?string
    {
        return $slug ? self::page($slug, $locale)['h1'] ?? null : null;
    }

    public static function cardCopy(?string $slug, ?string $locale = null): ?string
    {
        return $slug ? self::page($slug, $locale)['card'] ?? null : null;
    }

    /** Services that have both a database row and a page, in display order. */
    public static function published()
    {
        return Service::ordered()->whereNotNull('slug')->get()
            ->filter(fn (Service $service) => self::has($service->slug));
    }
}
