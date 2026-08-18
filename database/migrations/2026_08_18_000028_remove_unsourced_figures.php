<?php

use App\Models\UiString;
use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Take the unsourced figures off the site.
 *
 * "300+ projects delivered", "180+ happy clients", "15+ years in software" and
 * a "98% satisfaction rate" were all claimed without evidence, and the first
 * and third contradicted each other in public. The band and the About badge
 * now come from config/stats.php, which is null throughout, so neither
 * renders — but the 98% was also written into the Why GoSoftware copy, where
 * no config switch reaches it.
 *
 * That copy is overwritten with commitments the site actually makes and can
 * keep: fixed scope and price before starting, and a reply within one working
 * day. "15+ yrs" is deleted outright — nothing rendered it any more.
 */
return new class extends Migration
{
    public function up(): void
    {
        UiStringDefaults::syncMissing();

        $defaults = UiStringDefaults::all();

        foreach (['why2T', 'why2D'] as $key) {
            UiString::where('key', $key)->update(['value' => $defaults[$key]]);
        }

        UiString::where('key', 'yrsBadge')->delete();

        Cache::forget('gs.strings');
    }

    public function down(): void
    {
        // Restoring a claim we cannot evidence is not an improvement.
    }
};
