<?php

use App\Models\UiString;
use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Copy for the multi-step qualifying form (CRO-18). thanksB is overwritten
 * because the success message now says what the reply will contain.
 */
return new class extends Migration
{
    public function up(): void
    {
        UiStringDefaults::syncMissing();

        UiString::where('key', 'thanksB')->update(['value' => UiStringDefaults::all()['thanksB']]);

        Cache::forget('gs.strings');
    }

    public function down(): void
    {
        //
    }
};
