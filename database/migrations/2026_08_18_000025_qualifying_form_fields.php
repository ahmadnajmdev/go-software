<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The contact form asks qualifying questions now, so the lead row has somewhere
 * to put the answers. Budget and timeline are optional by design — the point of
 * offering "Not sure yet" and "Just exploring" is that the question stops
 * causing abandonment and starts producing answers.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->string('company')->nullable()->after('phone');
            $table->string('budget')->nullable()->after('service');
            $table->string('timeline')->nullable()->after('budget');
        });
    }

    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropColumn(['company', 'budget', 'timeline']);
        });
    }
};
