<?php

use App\Models\Section;
use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Two sections directly below the hero trust bar.
 *
 * The industry router is how one home page serves a pharmacy, a courier and a
 * property agency without the hero having to speak to all three at once.
 * The problem section is where the primary buyer recognises their own week.
 */
return new class extends Migration
{
    public function up(): void
    {
        UiStringDefaults::syncMissing();

        $after = Section::where('key', 'hero')->value('position') ?? 1;

        Section::where('position', '>', $after)->increment('position', 2);
        Section::updateOrCreate(['key' => 'problem'], ['position' => $after + 1, 'visible' => true]);
        Section::updateOrCreate(['key' => 'industries'], ['position' => $after + 2, 'visible' => true]);

        Cache::forget('gs.sections');
    }

    public function down(): void
    {
        Section::whereIn('key', ['problem', 'industries'])->delete();
        Cache::forget('gs.sections');
    }
};
