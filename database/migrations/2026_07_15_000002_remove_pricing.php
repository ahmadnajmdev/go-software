<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// The pricing section was removed from the site entirely.
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pricing_plans');

        if (Schema::hasTable('sections')) {
            DB::table('sections')->where('key', 'pricing')->delete();
        }

        if (Schema::hasTable('ui_strings')) {
            DB::table('ui_strings')->where('group', 'Pricing')->orWhere('key', 'navPricing')->delete();
        }
    }

    public function down(): void
    {
        // irreversible — pricing was removed from the product
    }
};
