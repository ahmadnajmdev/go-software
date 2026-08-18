<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Remembers the campaign a visitor arrived on so the contact form can record
 * it, however many pages later they submit.
 *
 * Captured server-side rather than through hidden fields: the UTM parameters
 * sit on the *landing* URL, which is usually not the page the form is on, and
 * this way attribution survives a visitor with JavaScript disabled.
 */
class CaptureAttribution
{
    public const KEY = 'gs.attribution';

    private const PARAMS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') || $request->is('admin', 'admin/*')) {
            return $next($request);
        }

        $utm = [];

        foreach (self::PARAMS as $param) {
            $value = $request->query($param);

            if (is_string($value) && filled($value)) {
                $utm[$param] = Str::limit($value, 190, '');
            }
        }

        // First touch wins. The campaign that brought someone to the site is
        // the one that earned the lead — not whichever link they clicked last
        // during the same visit.
        if ($utm && ! $request->session()->has(self::KEY)) {
            $request->session()->put(self::KEY, $utm);
        }

        return $next($request);
    }
}
