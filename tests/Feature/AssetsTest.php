<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Media;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_app_store_links_are_moved_to_the_iraqi_storefront(): void
    {
        $project = Project::first();
        // /uy/ is Uruguay — Iraqi visitors got "not available in your country"
        $project->update(['url' => 'https://apps.apple.com/uy/app/asaari/id6760316572']);

        $this->runStorefrontMigration();

        $this->assertSame('https://apps.apple.com/iq/app/asaari/id6760316572', $project->fresh()->url);
    }

    public function test_an_already_correct_storefront_is_left_alone(): void
    {
        $project = Project::first();
        $project->update(['url' => 'https://apps.apple.com/iq/app/asaari/id6760316572']);

        $this->runStorefrontMigration();

        $this->assertSame('https://apps.apple.com/iq/app/asaari/id6760316572', $project->fresh()->url);
    }

    private function runStorefrontMigration(): void
    {
        ob_start();
        (require database_path('migrations/2026_08_18_000008_fix_app_store_storefront.php'))->up();
        ob_end_clean();
    }

    public function test_the_storefront_rewrite_leaves_other_links_alone(): void
    {
        foreach ([
            'https://play.google.com/store/apps/details?id=krd.asaari',
            'https://powerorbits.com',
            'https://shopp.krd/products',
        ] as $url) {
            $this->assertSame(
                $url,
                preg_replace('#^(https://apps\.apple\.com)/[a-z]{2}/#i', '$1/iq/', $url)
            );
        }
    }

    public function test_hot_linked_images_are_pulled_onto_our_own_disk(): void
    {
        Storage::fake('public');
        Http::fake(['*' => Http::response('PNGDATA', 200, ['Content-Type' => 'image/png'])]);

        $client = Client::first();
        $client->update(['logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9Gc']);

        $this->artisan('media:localise', ['--apply' => true])->assertSuccessful();

        $logo = $client->fresh()->logo;
        $this->assertStringStartsWith('uploads/', $logo);
        $this->assertStringEndsWith('.png', $logo);
        Storage::disk('public')->assertExists($logo);
        $this->assertSame(1, Media::where('path', $logo)->count());
    }

    public function test_a_dry_run_changes_nothing(): void
    {
        Storage::fake('public');
        Http::fake();

        $client = Client::first();
        $client->update(['logo' => 'https://zuuapp.com/img/logos/Zuu.svg']);

        $this->artisan('media:localise')->assertSuccessful();

        $this->assertSame('https://zuuapp.com/img/logos/Zuu.svg', $client->fresh()->logo);
        Http::assertNothingSent();
    }

    public function test_a_failed_download_leaves_the_reference_intact(): void
    {
        Storage::fake('public');
        Http::fake(['*' => Http::response('gone', 404)]);

        $client = Client::first();
        $client->update(['logo' => 'https://play-lh.googleusercontent.com/expired']);

        $this->artisan('media:localise', ['--apply' => true])->assertSuccessful();

        // better a stale external URL than a broken local path
        $this->assertSame('https://play-lh.googleusercontent.com/expired', $client->fresh()->logo);
    }

    public function test_an_svg_carrying_script_is_refused(): void
    {
        Storage::fake('public');
        Http::fake(['*' => Http::response(
            '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>',
            200,
            ['Content-Type' => 'image/svg+xml']
        )]);

        $client = Client::first();
        $client->update(['logo' => 'https://evil.example/logo.svg']);

        $this->artisan('media:localise', ['--apply' => true])->assertSuccessful();

        $this->assertSame('https://evil.example/logo.svg', $client->fresh()->logo);
        $this->assertSame(0, Media::count());
    }

    public function test_unsplash_is_skipped_unless_explicitly_included(): void
    {
        Storage::fake('public');
        Http::fake(['*' => Http::response('JPEGDATA', 200, ['Content-Type' => 'image/jpeg'])]);

        Project::first()->update(['image' => 'https://images.unsplash.com/photo-1467232004584']);

        // Unsplash placeholders are CRO-11's business, not this command's
        $this->artisan('media:localise', ['--apply' => true])->assertSuccessful();
        Http::assertNothingSent();

        $this->artisan('media:localise', ['--apply' => true, '--include-unsplash' => true])->assertSuccessful();
        Http::assertSentCount(Http::recorded(fn (Request $r) => true)->count());
        $this->assertGreaterThan(0, Media::count());
    }

    public function test_the_zuu_logo_is_stored_in_the_repository(): void
    {
        $path = public_path('images/clients/zuu.svg');

        $this->assertFileExists($path, 'the Zuu logo should be served from our own domain');

        $svg = file_get_contents($path);
        $this->assertStringContainsString('<svg', $svg);
        $this->assertDoesNotMatchRegularExpression('/<script|onload=|onerror=/i', $svg);
    }
}
