<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lead attribution. Without these, a submission says what someone asked for
 * but nothing about which campaign, page or language produced it — which is
 * the half that tells us where to spend.
 *
 * These extend contact_submissions rather than starting a separate `leads`
 * table: the admin inbox, its unread counter and the existing rows all hang
 * off this table already, and forking it would split the lead history in two.
 */
return new class extends Migration
{
    private const COLUMNS = [
        'source', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
    ];

    public function up(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            foreach (self::COLUMNS as $column) {
                $table->string($column)->nullable();
            }

            $table->string('ip', 45)->nullable();      // 45 = longest IPv6 form
            $table->text('user_agent')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropColumn([...self::COLUMNS, 'ip', 'user_agent']);
        });
    }
};
