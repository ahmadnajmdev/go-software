<?php

use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Projects become real pages.
 *
 * Seven projects had no detail page at all; four linked straight off-site with
 * no way back, and three had no link whatsoever. The section that should
 * create the most desire was the one causing the most exits.
 *
 * Two taxonomies now. Categories gain a `kind` so the same table and the same
 * admin CRUD serve both: `industry` (who the client is — the primary filter,
 * because buyers recognise their own industry long before they recognise
 * "web app") and `type` (what we built — kept as the secondary filter).
 */
return new class extends Migration
{
    public const INDUSTRIES = [
        'retail' => ['en' => 'Retail & shops', 'ar' => 'التجزئة والمحلات', 'ckb' => 'فرۆشتن و دوکان'],
        'food' => ['en' => 'Restaurants & cafés', 'ar' => 'المطاعم والمقاهي', 'ckb' => 'چێشتخانە و کافێ'],
        'education' => ['en' => 'Academies & schools', 'ar' => 'الأكاديميات والمدارس', 'ckb' => 'ئەکادیمیا و قوتابخانە'],
        'real-estate' => ['en' => 'Real estate', 'ar' => 'العقارات', 'ckb' => 'خانووبەرە'],
        'logistics' => ['en' => 'Delivery & logistics', 'ar' => 'التوصيل واللوجستيات', 'ckb' => 'گەیاندن و لۆجستی'],
        'ecommerce' => ['en' => 'E-commerce', 'ar' => 'التجارة الإلكترونية', 'ckb' => 'بازرگانی ئۆنڵاین'],
        'services' => ['en' => 'Services', 'ar' => 'الخدمات', 'ckb' => 'خزمەتگوزاری'],
    ];

    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('kind', 12)->default('type')->after('slug');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('position');
            $table->foreignId('industry_id')->nullable()->after('category_id')
                ->constrained('categories')->nullOnDelete();

            // The story. All per-locale, all optional — a page renders whatever
            // exists and omits the rest, so nothing is ever invented to fill a
            // section. See BLOCKED.md.
            $table->string('client')->nullable();
            $table->json('outcome')->nullable();     // the one-line result, for the hero
            $table->json('problem')->nullable();
            $table->json('solution')->nullable();
            $table->json('result')->nullable();      // 2–3 quantified outcomes
            $table->json('quote')->nullable();
            $table->string('quote_author')->nullable();
            $table->string('quote_role')->nullable();
            $table->json('screenshots')->nullable();
            $table->string('technology')->nullable();
            $table->string('platforms')->nullable();
            $table->string('timeline')->nullable();
            $table->string('live_since')->nullable();
        });

        $position = (int) Category::max('position');

        foreach (self::INDUSTRIES as $slug => $name) {
            Category::firstOrCreate(
                ['slug' => $slug],
                ['kind' => 'industry', 'position' => ++$position, 'name' => $name],
            );
        }

        // Existing rows keep working: a slug from the title, and the type
        // taxonomy they already had.
        foreach (Project::all() as $project) {
            $slug = Str::slug($project->tr('title', 'en')) ?: 'project-'.$project->id;
            $candidate = $slug;
            $n = 1;

            while (Project::where('slug', $candidate)->exists()) {
                $candidate = $slug.'-'.++$n;
            }

            $project->update(['slug' => $candidate]);
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('industry_id');
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'client', 'outcome', 'problem', 'solution', 'result',
                'quote', 'quote_author', 'quote_role', 'screenshots', 'technology',
                'platforms', 'timeline', 'live_since']);
        });

        Category::where('kind', 'industry')->delete();

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('kind');
        });
    }
};
