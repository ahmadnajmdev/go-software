<?php

use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;

/** Copy for the project detail pages added by CRO-14. */
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
