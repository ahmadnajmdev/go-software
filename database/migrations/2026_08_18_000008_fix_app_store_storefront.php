<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;

/**
 * Point App Store links at the Iraqi storefront.
 *
 * The Asaari link used the /uy/ (Uruguay) storefront, which shows Iraqi
 * visitors "not available in your country" — the app is fine, the link was
 * wrong. Apple resolves /iq/ for this market.
 *
 * Applies to whatever storefront each row actually has, because project URLs
 * are entered in the admin panel and differ per install.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (Project::whereNotNull('url')->get() as $project) {
            $fixed = preg_replace(
                '#^(https://apps\.apple\.com)/[a-z]{2}/#i',
                '$1/iq/',
                $project->url
            );

            if ($fixed !== $project->url) {
                $project->update(['url' => $fixed]);
                echo "  App Store link → {$fixed}\n";
            }
        }
    }

    public function down(): void
    {
        // Reverting would send Iraqi visitors back to a foreign storefront.
    }
};
