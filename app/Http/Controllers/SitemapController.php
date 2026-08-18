<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use App\Support\ServiceCatalogue;
use Illuminate\Http\Response;

/**
 * Every page, in every language, with hreflang alternates on each entry so
 * search engines pair the three versions rather than treating them as
 * duplicates competing with each other.
 */
class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $paths = ['' => '1.0', 'projects' => '0.8', 'privacy-policy' => '0.3', 'terms-of-service' => '0.3'];

        foreach (ServiceCatalogue::published() as $service) {
            $paths['services/'.$service->slug] = '0.9';
        }

        $urls = [];

        foreach ($paths as $path => $priority) {
            foreach (SetLocale::LOCALES as $locale) {
                $urls[] = [
                    'loc' => $this->url($locale, $path),
                    'priority' => $priority,
                    'alternates' => collect(SetLocale::LOCALES)
                        ->mapWithKeys(fn ($alt) => [$alt => $this->url($alt, $path)])
                        ->all(),
                ];
            }
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    private function url(string $locale, string $path): string
    {
        $prefix = $locale === 'en' ? '' : '/'.$locale;

        return url($prefix.($path ? '/'.$path : '')) ?: url('/');
    }
}
