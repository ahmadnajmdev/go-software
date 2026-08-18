<?php

use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;

/**
 * Insert every default string this install has never seen.
 *
 * Site copy lives in the database, so keys added to the codebase after an
 * install was seeded never reach it — and t() falls back to returning the key
 * name, which is why catAll, projNone, visitUs and getDirections were rendering
 * as literal text on the live site while being perfectly present in the repo.
 *
 * Existing rows are untouched, so copy edited in the admin panel survives.
 */
return new class extends Migration
{
    public function up(): void
    {
        $created = UiStringDefaults::syncMissing();

        if ($created) {
            echo '  Added '.count($created).' missing UI strings: '.implode(', ', $created)."\n";
        }
    }

    public function down(): void
    {
        // Deleting these would only bring the raw keys back.
    }
};
