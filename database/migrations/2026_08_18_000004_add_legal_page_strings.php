<?php

use App\Models\UiString;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    private const KEYS = ['lastUpdated', 'legalContact'];

    public function up(): void
    {
        $defaults = require database_path('seeders/data/ui_strings.php');

        foreach (self::KEYS as $key) {
            UiString::firstOrCreate(['key' => $key], ['value' => $defaults[$key], 'group' => 'Footer']);
        }

        Cache::forget('gs.strings');
    }

    public function down(): void
    {
        UiString::whereIn('key', self::KEYS)->delete();
        Cache::forget('gs.strings');
    }
};
