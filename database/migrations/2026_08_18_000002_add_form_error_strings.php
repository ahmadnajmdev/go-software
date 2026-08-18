<?php

use App\Models\UiString;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Validation messages for the contact form, in all three languages.
 *
 * Inserted rather than seeded: re-running the seeder would overwrite every
 * string edited in the admin panel. Existing keys are left untouched.
 */
return new class extends Migration
{
    private const KEYS = ['errGeneric', 'errName', 'errEmail', 'errEmailValid', 'errMessage', 'errTooLong'];

    public function up(): void
    {
        $defaults = require database_path('seeders/data/ui_strings.php');

        foreach (self::KEYS as $key) {
            UiString::firstOrCreate(['key' => $key], ['value' => $defaults[$key], 'group' => 'Contact']);
        }

        Cache::forget('gs.strings');
    }

    public function down(): void
    {
        UiString::whereIn('key', self::KEYS)->delete();

        Cache::forget('gs.strings');
    }
};
