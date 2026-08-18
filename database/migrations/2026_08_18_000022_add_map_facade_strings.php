<?php

use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;

/** Copy for the click-to-load map (CRO-21). */
return new class extends Migration
{
    public function up(): void
    {
        UiStringDefaults::syncMissing();
    }

    public function down(): void
    {
        //
    }
};
