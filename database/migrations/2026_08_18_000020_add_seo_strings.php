<?php

use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;

/** Per-page titles and meta descriptions added by CRO-22. */
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
