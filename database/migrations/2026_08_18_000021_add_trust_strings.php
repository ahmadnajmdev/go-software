<?php

use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;

/** Legal entity, warranty and support-tier copy added by CRO-23. */
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
