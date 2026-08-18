<?php

use App\Models\UiString;
use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * The logo strip is now honestly labelled "Companies we work with" rather than
 * "Testimonials", so those two headings are overwritten; the quote-specific
 * headings are new.
 */
return new class extends Migration
{
    public function up(): void
    {
        UiStringDefaults::syncMissing();

        $defaults = UiStringDefaults::all();

        foreach (['tstTitle', 'tstTag'] as $key) {
            UiString::where('key', $key)->update(['value' => $defaults[$key]]);
        }

        Cache::forget('gs.strings');
    }

    public function down(): void
    {
        //
    }
};
