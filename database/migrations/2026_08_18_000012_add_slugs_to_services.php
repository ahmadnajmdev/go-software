<?php

use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Services become real pages, so each needs a stable slug.
 *
 * Existing rows are matched to a slug by their tag rather than by ID, because
 * IDs differ per install and the rows are admin-managed. POS, e-commerce and
 * support are added as new services — all three are things GoSoftware sells
 * and none of them appeared anywhere on the site, in any language.
 */
return new class extends Migration
{
    private const BY_TAG = [
        'WEB' => 'website-development',
        'WEBSITE' => 'website-development',
        'WEB APPS' => 'web-applications',
        'WEB APP' => 'web-applications',
        'MOBILE' => 'mobile-app-development',
        'SYSTEMS' => 'management-systems',
        'SYSTEM' => 'management-systems',
        'POS' => 'pos-inventory',
        'ECOMMERCE' => 'ecommerce',
        'SUPPORT' => 'support-and-maintenance',
    ];

    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('position');
        });

        foreach (Service::all() as $service) {
            $slug = self::BY_TAG[strtoupper(trim((string) $service->tag))] ?? null;

            if ($slug && ! Service::where('slug', $slug)->exists()) {
                $service->update(['slug' => $slug]);
            }
        }

        $catalogue = require resource_path('services/catalogue.php');
        $position = (int) Service::max('position');

        foreach (['pos-inventory', 'ecommerce', 'support-and-maintenance'] as $slug) {
            if (Service::where('slug', $slug)->exists()) {
                continue;
            }

            $page = $catalogue[$slug];

            Service::create([
                'slug' => $slug,
                'position' => ++$position,
                'tag' => $page['tag'],
                'title' => self::perLocale($page, 'name'),
                'description' => self::perLocale($page, 'card'),
            ]);
        }
    }

    public function down(): void
    {
        Service::whereIn('slug', ['pos-inventory', 'ecommerce', 'support-and-maintenance'])->delete();

        Schema::table('services', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }

    private static function perLocale(array $page, string $field): array
    {
        return collect(['en', 'ar', 'ckb'])
            ->mapWithKeys(fn ($locale) => [$locale => $page[$locale][$field]])
            ->all();
    }
};
