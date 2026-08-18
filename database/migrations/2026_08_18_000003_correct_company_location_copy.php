<?php

use App\Models\UiString;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * The site described the company as UK-based while showing an Erbil address,
 * an Erbil phone number and Erbil clients. These four strings are overwritten
 * rather than skipped-if-present: they are factually wrong wherever they still
 * say otherwise, including copy edited through the admin panel.
 */
return new class extends Migration
{
    private const KEYS = ['location', 'aboutBody', 'ftBlurb', 'h1Tag'];

    public function up(): void
    {
        $defaults = require database_path('seeders/data/ui_strings.php');

        foreach (self::KEYS as $key) {
            UiString::where('key', $key)->update(['value' => $defaults[$key]]);
        }

        Cache::forget('gs.strings');
    }

    public function down(): void
    {
        // No going back to a false claim about where the company is.
    }
};
