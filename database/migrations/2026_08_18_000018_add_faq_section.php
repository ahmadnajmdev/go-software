<?php

use App\Models\Section;
use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * The FAQ goes directly above the final CTA — the objections have to be
 * answered before the form, not after it.
 */
return new class extends Migration
{
    public function up(): void
    {
        UiStringDefaults::syncMissing();

        $contact = Section::where('key', 'contact')->first();
        $position = $contact?->position ?? Section::max('position') + 1;

        Section::where('position', '>=', $position)->increment('position');
        Section::updateOrCreate(['key' => 'faq'], ['position' => $position, 'visible' => true]);

        Cache::forget('gs.sections');
    }

    public function down(): void
    {
        Section::where('key', 'faq')->delete();
        Cache::forget('gs.sections');
    }
};
