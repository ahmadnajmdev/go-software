<?php

namespace App\Support;

/**
 * Resolves configured social profile URLs.
 *
 * The site used to render every icon unconditionally, defaulting missing ones
 * to "#" — eleven dead links on a software company's own website. Anything
 * that is not a real http(s) URL is dropped here instead, so an unconfigured
 * channel renders as nothing at all.
 */
class Social
{
    /** @return array<string, string> network => URL, in config order */
    public static function company(): array
    {
        return self::resolve(config('social.networks', []), 'social.');
    }

    /** @return array<string, string> network => URL, in config order */
    public static function founder(): array
    {
        return self::resolve(config('social.founder_networks', []), 'founder.');
    }

    private static function resolve(array $networks, string $prefix): array
    {
        $links = [];

        foreach (array_keys($networks) as $network) {
            $url = Settings::get($prefix.$network);

            if (self::isProfileUrl($url)) {
                $links[$network] = $url;
            }
        }

        return $links;
    }

    /** "#", "", null and javascript: are all treated as "not configured". */
    private static function isProfileUrl(mixed $url): bool
    {
        return is_string($url)
            && preg_match('#^https?://#i', $url) === 1
            && filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
