<?php

use App\Models\Section;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Move the founder section from roughly 80% scroll depth to roughly 45%.
 *
 * A named, photographed, personally accountable founder is the strongest and
 * least copyable trust asset the site has, and it sat below the point any
 * phone visitor arriving from Instagram ever reaches.
 *
 * The brief asks for it directly after the case studies and before "Why
 * GoSoftware" — which also means the case studies have to come before "Why",
 * and they did not. Both move.
 */
return new class extends Migration
{
    private const ORDER = [
        'hero', 'strip', 'about', 'services',
        'projects',   // featured case studies
        'founder',    // ~45% scroll
        'why', 'process', 'stats', 'testimonials', 'contact',
    ];

    public function up(): void
    {
        foreach (self::ORDER as $i => $key) {
            Section::where('key', $key)->update(['position' => $i + 1]);
        }

        Cache::forget('gs.sections');
    }

    public function down(): void
    {
        foreach (['hero', 'strip', 'about', 'services', 'why', 'process',
            'projects', 'stats', 'founder', 'testimonials', 'contact'] as $i => $key) {
            Section::where('key', $key)->update(['position' => $i + 1]);
        }

        Cache::forget('gs.sections');
    }
};
