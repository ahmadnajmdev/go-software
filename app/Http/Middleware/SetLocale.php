<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const LOCALES = ['en', 'ar', 'ckb'];

    /**
     * Locale comes from the URL path (/, /ar, /ckb) so every language
     * has its own crawlable URL. English lives at the root.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale') ?? 'en';

        if (! in_array($locale, self::LOCALES)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        if ($locale !== 'en') {
            // keep generated URLs (route('home') etc.) on the current locale
            URL::defaults(['locale' => $locale]);
        }

        return $next($request);
    }
}
