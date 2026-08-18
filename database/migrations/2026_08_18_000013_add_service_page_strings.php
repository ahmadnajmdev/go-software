<?php

use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;

/** Shared copy for the service pages added by CRO-13. */
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
