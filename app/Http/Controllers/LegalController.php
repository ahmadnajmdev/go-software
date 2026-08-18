<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function privacy()
    {
        return $this->page('privacy', 'privacy-policy');
    }

    public function terms()
    {
        return $this->page('terms', 'terms-of-service');
    }

    /**
     * Legal copy lives in resources/legal/*.php rather than ui_strings — it is
     * a document to be reviewed whole, not a label to be tweaked in the admin
     * panel. English is the fallback if a language is ever missing.
     */
    private function page(string $document, string $slug)
    {
        $content = require resource_path("legal/{$document}.php");
        $locale = app()->getLocale();

        return view('legal', [
            'slug' => $slug,
            'updated' => $content['updated'],
            'doc' => $content[$locale] ?? $content['en'],
        ]);
    }
}
