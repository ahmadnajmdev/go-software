<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// The blog was removed from the site, and the team grid was replaced
// by a single founder/CEO introduction section.
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('team_members');

        if (Schema::hasTable('sections')) {
            DB::table('sections')->where('key', 'blog')->delete();
            DB::table('sections')->where('key', 'team')->update(['key' => 'founder']);
        }

        if (Schema::hasTable('ui_strings')) {
            DB::table('ui_strings')
                ->whereIn('group', ['Blog', 'Team'])
                ->orWhereIn('key', ['navBlog', 'ftBlog', 'ftTeam'])
                ->delete();
        }

        Cache::forget('gs.sections');
        Cache::forget('gs.strings');
    }

    public function down(): void
    {
        // irreversible — blog and the team grid were removed from the product
    }
};
