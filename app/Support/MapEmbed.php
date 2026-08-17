<?php

namespace App\Support;

class MapEmbed
{
    /** Hosts we will put inside an iframe. Anything else is rejected. */
    private const ALLOWED_HOSTS = ['google.com', 'www.google.com', 'maps.google.com'];

    /**
     * A Google Maps embed pasted by the admin — either the whole
     * "<iframe …>" snippet from Share → Embed a map, or just its URL.
     * Returns null when the value is blank or is not a Google Maps URL,
     * so a bad paste falls back to the coordinates instead of framing
     * an arbitrary site.
     */
    public static function custom(?string $raw): ?string
    {
        if (blank($raw)) {
            return null;
        }

        $raw = trim($raw);

        if (preg_match('/\bsrc\s*=\s*["\']([^"\']+)["\']/i', $raw, $m)) {
            $raw = trim($m[1]);
        }

        return static::isGoogleMaps($raw) ? $raw : null;
    }

    public static function isGoogleMaps(string $url): bool
    {
        $parts = parse_url($url);

        if (! $parts || ($parts['scheme'] ?? '') !== 'https') {
            return false;
        }

        if (! in_array(strtolower($parts['host'] ?? ''), self::ALLOWED_HOSTS, true)) {
            return false;
        }

        return str_starts_with($parts['path'] ?? '', '/maps');
    }

    /**
     * Key-less Google Maps embed. Google's `q` accepts either "lat,lng" or a
     * place name, so coordinates and place lookups share one builder.
     */
    public static function forQuery(string $query, int $zoom, string $locale = 'en'): string
    {
        return 'https://maps.google.com/maps?'.http_build_query([
            'q' => $query,
            'z' => $zoom,
            'hl' => $locale,
            'output' => 'embed',
        ]);
    }

    public static function forCoordinates(float $lat, float $lng, int $zoom, string $locale = 'en'): string
    {
        return static::forQuery($lat.','.$lng, $zoom, $locale);
    }

    public static function directions(float $lat, float $lng): string
    {
        return 'https://www.google.com/maps/dir/?'.http_build_query([
            'api' => 1,
            'destination' => $lat.','.$lng,
        ]);
    }

    /** Fallback when a custom embed is set but no coordinates are. */
    public static function search(string $query): string
    {
        return 'https://www.google.com/maps/search/?'.http_build_query([
            'api' => 1,
            'query' => $query,
        ]);
    }

    /** Google only ships a subset of locales; ckb has no `hl` code. */
    public static function locale(string $appLocale): string
    {
        return $appLocale === 'ar' ? 'ar' : 'en';
    }
}
