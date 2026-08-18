<?php

use App\Support\UiStringDefaults;
use Illuminate\Database\Migrations\Migration;

/** The WhatsApp labels and pre-written messages added by CRO-06. */
return new class extends Migration
{
    public function up(): void
    {
        UiStringDefaults::syncMissing();
    }

    public function down(): void
    {
        // Removing them would only make the raw keys render again.
    }
};
