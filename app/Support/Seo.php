<?php

namespace App\Support;

use App\Http\Middleware\SetLocale;

/**
 * Per-page SEO: language alternates, canonical, and structured data.
 *
 * The alternates used to be hardcoded to the home page on every page, so
 * /projects told Google its Kurdish equivalent was the Kurdish home page.
 * They are derived from the current path here instead.
 */
class Seo
{
    /** The current path with any locale prefix removed: "ckb/projects" → "projects". */
    public static function basePath(): string
    {
        $segments = explode('/', trim(request()->path(), '/'));

        if (in_array($segments[0] ?? '', SetLocale::LOCALES, true) && $segments[0] !== 'en') {
            array_shift($segments);
        }

        return trim(implode('/', $segments), '/');
    }

    /** @return array<string, string> locale => absolute URL for this same page */
    public static function alternates(): array
    {
        $base = self::basePath();

        return [
            'en' => url('/'.$base),
            'ar' => url('/ar'.($base ? '/'.$base : '')),
            'ckb' => url('/ckb'.($base ? '/'.$base : '')),
        ];
    }

    public static function canonical(): string
    {
        return self::alternates()[app()->getLocale()] ?? url()->current();
    }

    /**
     * LocalBusiness — the physical office, so the site can rank for "software
     * company in Erbil" and show up in Maps.
     */
    public static function localBusiness(): array
    {
        $lat = gs_setting('contact.map_lat');
        $lng = gs_setting('contact.map_lng');

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            '@id' => url('/').'#business',
            'name' => config('app.name'),
            'url' => url('/'),
            'description' => t('heroSub'),
            'image' => self::ogImage(),
            'logo' => asset('images/logo-dark.png'),
            'telephone' => gs_setting('contact.phone'),
            'email' => gs_setting('contact.email'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Justice Tower, Floor 16, Office 21',
                'addressLocality' => 'Erbil',
                'addressRegion' => 'Kurdistan Region',
                'addressCountry' => 'IQ',
            ],
            'areaServed' => [
                ['@type' => 'AdministrativeArea', 'name' => 'Kurdistan Region'],
                ['@type' => 'Country', 'name' => 'Iraq'],
            ],
            'availableLanguage' => ['ckb', 'ar', 'en'],
            'openingHoursSpecification' => [[
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'],
                'opens' => '09:00',
                'closes' => '17:00',
            ]],
        ];

        if (is_numeric($lat) && is_numeric($lng)) {
            $schema['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => (float) $lat, 'longitude' => (float) $lng];
        }

        if ($social = array_values(Social::company())) {
            $schema['sameAs'] = $social;
        }

        return $schema;
    }

    /** Organization, naming the founder. */
    public static function organization(): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => url('/').'#organization',
            'name' => config('app.name'),
            'url' => url('/'),
            'logo' => asset('images/logo-dark.png'),
            'founder' => [
                '@type' => 'Person',
                'name' => gs_setting('about.ceo_name'),
                'jobTitle' => t('ceoRole'),
            ],
        ];

        if ($social = array_values(Social::company())) {
            $schema['sameAs'] = $social;
        }

        return $schema;
    }

    /** @param array<int, array{name: string, url: ?string}> $crumbs */
    public static function breadcrumbs(array $crumbs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($crumbs)->values()->map(fn ($crumb, $i) => array_filter([
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'] ?? null,
            ]))->all(),
        ];
    }

    public static function ogImage(): string
    {
        return asset('images/og-default.png');
    }
}
