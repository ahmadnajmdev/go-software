<?php

use App\Models\UiString;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

if (! function_exists('t')) {
    /**
     * Localized UI string by design key (e.g. t('aboutTitle')).
     * Values come from the ui_strings table, cached across requests.
     */
    function t(string $key, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        $strings = Cache::rememberForever('gs.strings', function () {
            if (! Schema::hasTable('ui_strings')) {
                return [];
            }

            return UiString::pluck('value', 'key')->all();
        });

        $value = $strings[$key] ?? [];

        return $value[$locale] ?? $value['en'] ?? $key;
    }
}

if (! function_exists('media_url')) {
    /**
     * Resolve an image reference: absolute URLs pass through (seeded
     * Unsplash images), storage paths resolve through the public disk,
     * and images/* paths serve straight from /public.
     */
    function media_url(?string $image): string
    {
        if (blank($image)) {
            return '';
        }

        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        if (str_starts_with($image, 'images/')) {
            return asset($image);
        }

        return Storage::disk('public')->url($image);
    }
}

if (! function_exists('gs_date')) {
    /**
     * Localized "June 12, 2026"-style date for the current locale.
     * ckb is unknown to intl/Carbon, so month names are mapped by hand.
     */
    function gs_date(\Carbon\CarbonInterface $date, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        $months = [
            'ar' => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
                     'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            'ckb' => ['کانوونی دووەم', 'شوبات', 'ئازار', 'نیسان', 'ئایار', 'حوزەیران',
                      'تەمووز', 'ئاب', 'ئەیلوول', 'تشرینی یەکەم', 'تشرینی دووەم', 'کانوونی یەکەم'],
        ];

        if (! isset($months[$locale])) {
            return $date->format('F d, Y');
        }

        $month = $months[$locale][$date->month - 1];

        return $locale === 'ar'
            ? sprintf('%d %s %d', $date->day, $month, $date->year)
            : sprintf('%dی %sی %d', $date->day, $month, $date->year);
    }
}

if (! function_exists('gs_section_visible')) {
    /**
     * Whether a home-page section is visible to the public. Used to hide
     * nav/footer links that point at hidden sections. Admins keep all links.
     */
    function gs_section_visible(string $key): bool
    {
        $visibility = \Illuminate\Support\Facades\Cache::rememberForever('gs.sections', function () {
            if (! \Illuminate\Support\Facades\Schema::hasTable('sections')) {
                return [];
            }

            return \App\Models\Section::pluck('visible', 'key')->map(fn ($v) => (bool) $v)->all();
        });

        return $visibility[$key] ?? true;
    }
}

if (! function_exists('gs_setting')) {
    function gs_setting(string $key, mixed $default = null): mixed
    {
        return \App\Support\Settings::get($key, $default);
    }
}

if (! function_exists('gs_route')) {
    /**
     * Locale-aware site URL: gs_route('projects') gives /projects in English
     * and /ar/projects, /ckb/projects in the other locales — matching the
     * path-based locale routes.
     */
    function gs_route(string $path = ''): string
    {
        $locale = app()->getLocale();
        $prefix = $locale === 'en' ? '' : '/'.$locale;

        return url($prefix.'/'.ltrim($path, '/'));
    }
}

if (! function_exists('gs_setting_tr')) {
    /**
     * Localized value of a setting stored as a {en,ar,ckb} map, falling back
     * to English. Plain-string settings pass straight through.
     */
    function gs_setting_tr(string $key, mixed $default = null): string
    {
        $value = \App\Support\Settings::get($key, $default);

        if (! is_array($value)) {
            return (string) $value;
        }

        $locale = app()->getLocale();

        return $value[$locale] ?? $value['en'] ?? '';
    }
}
