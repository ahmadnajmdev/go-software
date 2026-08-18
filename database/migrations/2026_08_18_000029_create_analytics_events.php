<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * First-party analytics.
 *
 * The dataLayer events go to GTM, which is where GA4 and the pixels live —
 * but that is a third party, it needs an ID we do not have yet, and it cannot
 * be joined to the leads table. This stores the same events on our own disk so
 * the admin dashboard can answer the only questions that matter here: how many
 * people arrive, how many start the form, where they give up, and how many
 * finish.
 *
 * Deliberately holds no personal data. No IP address, no user agent, no
 * identifiers. `visitor` is a hash of the request fingerprint plus a salt that
 * rotates daily, so same-day sessions can be counted without anyone being
 * followed from one day to the next, and it cannot be reversed.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('page', 190)->nullable();
            $table->string('locale', 3)->nullable();

            // The two dimensions almost every report groups by: what was
            // interacted with, and where it was on the page.
            $table->string('label', 190)->nullable();
            $table->string('location', 60)->nullable();

            $table->json('params')->nullable();
            $table->char('visitor', 32)->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['name', 'created_at']);
            $table->index('created_at');
            $table->index(['visitor', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
