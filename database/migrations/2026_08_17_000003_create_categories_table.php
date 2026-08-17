<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Project categories were free text repeated on every project, which made
     * them impossible to filter on reliably. Promote them to their own table
     * and point projects at it, carrying the existing values across so nothing
     * has to be re-entered.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('position');
            $table->string('slug')->unique();
            $table->json('name');
            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('fit')
                ->constrained()->nullOnDelete();
        });

        $position = 0;
        $seen = [];

        foreach (DB::table('projects')->orderBy('position')->get() as $project) {
            $name = json_decode($project->category ?? '', true);

            if (! is_array($name) || blank($name['en'] ?? null)) {
                continue;
            }

            $key = mb_strtolower(trim($name['en']));

            if (! isset($seen[$key])) {
                $slug = Str::slug($name['en']) ?: 'category-'.($position + 1);
                $base = $slug;
                $n = 2;
                while (DB::table('categories')->where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$n++;
                }

                $seen[$key] = DB::table('categories')->insertGetId([
                    'position' => ++$position,
                    'slug' => $slug,
                    'name' => json_encode($name, JSON_UNESCAPED_UNICODE),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('projects')->where('id', $project->id)
                ->update(['category_id' => $seen[$key]]);
        }

        Schema::table('projects', fn (Blueprint $table) => $table->dropColumn('category'));
    }

    public function down(): void
    {
        Schema::table('projects', fn (Blueprint $table) => $table->json('category')->nullable());

        // put the category name back on each project before the table goes
        foreach (DB::table('projects')->whereNotNull('category_id')->get() as $project) {
            $name = DB::table('categories')->where('id', $project->category_id)->value('name');
            DB::table('projects')->where('id', $project->id)->update(['category' => $name]);
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
