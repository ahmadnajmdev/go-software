<?php

use App\Models\UiString;
use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * The form panel still said "Ask for a call back" and "Fill in the form and
 * we'll be in touch" — copy from before it became a four-step estimate
 * request. Overwritten rather than skipped, because it describes the wrong
 * thing now.
 */
return new class extends Migration
{
    public function up(): void
    {
        UiStringDefaults::syncMissing();

        $defaults = UiStringDefaults::all();

        foreach (['formTitle', 'formSub'] as $key) {
            UiString::where('key', $key)->update(['value' => $defaults[$key]]);
        }

        Cache::forget('gs.strings');
    }

    public function down(): void
    {
        //
    }
};
