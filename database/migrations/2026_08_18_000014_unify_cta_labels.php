<?php

use App\Models\UiString;
use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * One primary CTA label site-wide.
 *
 * "Send Message" is overwritten rather than skipped: the button no longer
 * sends a message, it asks for an estimate, and leaving the old label would
 * describe the wrong thing.
 */
return new class extends Migration
{
    public function up(): void
    {
        UiStringDefaults::syncMissing();

        $defaults = UiStringDefaults::all();
        UiString::where('key', 'sendMsg')->update(['value' => $defaults['sendMsg']]);

        Cache::forget('gs.strings');
    }

    public function down(): void
    {
        //
    }
};
