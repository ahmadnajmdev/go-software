<?php

use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;

/** Copy for the static hero that replaced the carousel (CRO-10). */
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
