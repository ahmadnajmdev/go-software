<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * The company figures moved to config/stats.php so they are stated in one
 * place. Drop the settings row so there is no second copy to disagree with it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Setting::where('key', 'stats.values')->delete();
        Cache::forget('gs.settings');
    }

    public function down(): void
    {
        //
    }
};
