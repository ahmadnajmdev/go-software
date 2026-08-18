<?php

use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;

/** Labels for the sticky mobile action bar added by CRO-07. */
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
