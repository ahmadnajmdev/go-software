<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Project tiles are tall and narrow, so "cover" crops a wide logo or
     * wordmark down to an unreadable fragment. Let each project say whether
     * its image should fill the tile or be shown whole.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('fit', 10)->default('cover')->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('fit');
        });
    }
};
