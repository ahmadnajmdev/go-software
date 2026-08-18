<?php

use App\Models\Testimonial;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Testimonials become real, or they do not render.
 *
 * The section was headed TESTIMONIALS and contained a client logo marquee and
 * nothing else — no quote, no name, no result. The heading primes a visitor to
 * look for endorsement and they find none, which is worse than having no
 * section at all.
 *
 * The three seeded testimonials ("Tom Harding", "Priya Nair", "Sarah Doyle")
 * are invented, so they are deleted here. Nothing is shown until a real one
 * exists.
 */
return new class extends Migration
{
    private const INVENTED = ['Tom Harding', 'Priya Nair', 'Sarah Doyle'];

    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('company')->nullable()->after('role');
            // One concrete outcome — "cut monthly stock-taking from 3 days to 2
            // hours" does more than five stars ever will.
            $table->json('result')->nullable()->after('quote');
            $table->string('video_url')->nullable()->after('avatar');
        });

        Testimonial::whereIn('author', self::INVENTED)->delete();
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['company', 'result', 'video_url']);
        });
    }
};
