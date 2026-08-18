<?php

namespace Tests\Feature;

use App\Models\ContactSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public static function locales(): array
    {
        return [['en'], ['ar'], ['ckb']];
    }

    #[DataProvider('locales')]
    public function test_home_renders_in_every_locale_path(string $locale): void
    {
        $this->get($locale === 'en' ? '/' : '/'.$locale)
            ->assertOk()
            ->assertSee(t('heroTitle', $locale), false);
    }

    public function test_locale_paths_and_hreflang_alternates(): void
    {
        $this->get('/nope')->assertNotFound(); // only ar|ckb locale prefixes exist

        $html = $this->get('/ar')->assertOk()->getContent();
        $this->assertStringContainsString('dir="rtl"', $html);
        $this->assertStringContainsString('hreflang="en"', $html);
        $this->assertStringContainsString('hreflang="ar"', $html);
        $this->assertStringContainsString('hreflang="ckb"', $html);
        $this->assertStringContainsString('hreflang="x-default"', $html);

        // generated links (route('home')) stay on the current locale path
        $this->assertStringContainsString(url('/ar').'#contact', $html);
    }

    public function test_contact_stores_and_honeypot_rejects(): void
    {
        $payload = [
            'name' => 'Jane', 'email' => 'jane@example.com', 'message' => 'Hello there',
            // the qualifying form made these two required
            'service' => 'website', 'phone' => '+9647510000000',
        ];

        $this->postJson('/contact', $payload)->assertOk()->assertJson(['ok' => true]);
        $this->assertSame(1, ContactSubmission::count());

        $this->postJson('/contact', $payload + ['website' => 'spam'])->assertStatus(422);
        $this->postJson('/contact', ['name' => 'X'])->assertStatus(422);
    }

    public function test_guests_blocked_from_admin_and_inline_api(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->postJson('/admin/api/inline-text', [])->assertStatus(401);
    }

    public function test_admin_login_and_inline_edit_per_locale(): void
    {
        $admin = User::first();

        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)->postJson('/admin/api/inline-text', [
            'type' => 'string', 'key' => 'aboutTitle', 'locale' => 'ar', 'value' => 'عنوان جديد',
        ])->assertOk();

        $this->assertSame('عنوان جديد', t('aboutTitle', 'ar'));
        $this->assertNotSame('عنوان جديد', t('aboutTitle', 'en'));
    }

    public function test_inline_api_rejects_non_whitelisted_targets(): void
    {
        $admin = User::first();

        $this->actingAs($admin)->postJson('/admin/api/inline-text', [
            'type' => 'string', 'key' => 'nope-not-a-key', 'locale' => 'en', 'value' => 'x',
        ])->assertStatus(404);

        $this->actingAs($admin)->postJson('/admin/api/inline-text', [
            'type' => 'model', 'model' => 'users', 'id' => 1, 'field' => 'password', 'locale' => 'en', 'value' => 'x',
        ])->assertStatus(422);
    }

    public function test_inline_image_updates_models_and_settings(): void
    {
        $admin = User::first();
        $service = \App\Models\Service::first();

        $this->actingAs($admin)->postJson('/admin/api/inline-image', [
            'type' => 'model', 'model' => 'services', 'id' => $service->id,
            'field' => 'image', 'value' => 'uploads/2026/07/new.jpg',
        ])->assertOk();
        $this->assertSame('uploads/2026/07/new.jpg', $service->fresh()->image);

        $this->actingAs($admin)->postJson('/admin/api/inline-image', [
            'type' => 'setting', 'key' => 'images.hero', 'value' => 'https://images.unsplash.com/photo-x?w=900',
        ])->assertOk();
        $this->assertSame('https://images.unsplash.com/photo-x?w=900', gs_setting('images.hero'));

        // non-whitelisted setting key and javascript: URL are rejected
        $this->actingAs($admin)->postJson('/admin/api/inline-image', [
            'type' => 'setting', 'key' => 'contact.email', 'value' => 'uploads/x.jpg',
        ])->assertStatus(422);
        $this->actingAs($admin)->postJson('/admin/api/inline-image', [
            'type' => 'setting', 'key' => 'images.hero', 'value' => 'javascript:alert(1)',
        ])->assertStatus(422);
    }

    public function test_inline_add_and_delete_items(): void
    {
        $admin = User::first();

        $before = \App\Models\Service::count();

        $this->actingAs($admin)->postJson('/admin/api/items', ['model' => 'services'])->assertOk();
        $this->assertSame($before + 1, \App\Models\Service::count());

        $new = \App\Models\Service::orderByDesc('position')->first();
        $this->actingAs($admin)->deleteJson('/admin/api/items', ['model' => 'services', 'id' => $new->id])->assertOk();
        $this->assertSame($before, \App\Models\Service::count());
    }

    public function test_sections_toggle_hides_from_guests(): void
    {
        $admin = User::first();

        $this->actingAs($admin)->postJson('/admin/api/sections', ['key' => 'founder', 'action' => 'toggle'])
            ->assertOk()->assertJson(['visible' => false]);

        auth()->logout();

        $this->get('/')->assertOk()->assertDontSee(t('founderTitle', 'en'));
    }

    public function test_hidden_sections_disappear_from_nav_and_footer(): void
    {
        $admin = User::first();

        $this->actingAs($admin)->postJson('/admin/api/sections', ['key' => 'founder', 'action' => 'toggle'])
            ->assertOk()->assertJson(['visible' => false]);

        auth()->logout();

        // guests: no founder footer link, no #founder anchor target
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('#founder', $html);
        $this->assertStringNotContainsString(t('ftFounder', 'en'), $html);

        // admins keep the link (they still see the section at reduced opacity)
        $adminHtml = $this->actingAs($admin)->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('#founder', $adminHtml);
    }

    public function test_pricing_is_fully_removed(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('#pricing', $html);

        $admin = User::first();
        $this->actingAs($admin)->get('/admin/plans')->assertNotFound();
        $this->actingAs($admin)->postJson('/admin/api/inline-text', [
            'type' => 'model', 'model' => 'plans', 'id' => 1, 'field' => 'name', 'locale' => 'en', 'value' => 'x',
        ])->assertStatus(422);
    }

    public function test_blog_is_fully_removed(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('#blog', $html);

        $this->get('/blog/anything')->assertNotFound();

        $admin = User::first();
        $this->actingAs($admin)->get('/admin/posts')->assertNotFound();
        $this->actingAs($admin)->postJson('/admin/api/inline-text', [
            'type' => 'model', 'model' => 'posts', 'id' => 1, 'field' => 'title', 'locale' => 'en', 'value' => 'x',
        ])->assertStatus(422);
    }

    public function test_team_grid_replaced_by_founder(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('id="founder"', $html);
        $this->assertStringContainsString(t('founderTitle', 'en'), $html);
        $this->assertStringContainsString(gs_setting('about.ceo_name'), $html);

        $admin = User::first();
        $this->actingAs($admin)->get('/admin/team')->assertNotFound();
        $this->actingAs($admin)->postJson('/admin/api/inline-text', [
            'type' => 'model', 'model' => 'team', 'id' => 1, 'field' => 'name', 'locale' => 'en', 'value' => 'x',
        ])->assertStatus(422);
    }

    public function test_project_form_uploads_image_and_registers_it_in_media(): void
    {
        Storage::fake('public');
        $admin = User::first();
        $project = \App\Models\Project::first();

        $this->actingAs($admin)->put("/admin/projects/{$project->id}", [
            'image' => $project->image,                       // uploaded file must win over this
            'image_file' => UploadedFile::fake()->image('office.jpg', 800, 600),
            'title' => ['en' => 'Uploaded project'],
            'category' => ['en' => 'WEBSITE'],
        ])->assertRedirect('/admin/projects');

        $path = $project->fresh()->image;
        $this->assertStringStartsWith('uploads/', $path);
        Storage::disk('public')->assertExists($path);
        $this->assertSame(1, \App\Models\Media::where('path', $path)->count());

        // no file posted → the pasted path/URL is kept as-is
        $this->actingAs($admin)->put("/admin/projects/{$project->id}", [
            'image' => 'https://images.unsplash.com/photo-y?w=600',
            'title' => ['en' => 'Uploaded project'],
            'category' => ['en' => 'WEBSITE'],
        ])->assertRedirect('/admin/projects');
        $this->assertSame('https://images.unsplash.com/photo-y?w=600', $project->fresh()->image);
    }

    public function test_client_logo_uploads_and_renders_in_the_marquee(): void
    {
        Storage::fake('public');
        $admin = User::first();
        $client = \App\Models\Client::ordered()->first();

        // seeded clients have no logo yet — the marquee falls back to the name
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString($client->name, $html);

        $this->actingAs($admin)->put("/admin/clients/{$client->id}", [
            'name' => $client->name,
            'logo_file' => UploadedFile::fake()->image('logo.png', 400, 120),
            'url' => 'https://northwind.example',
        ])->assertRedirect('/admin/clients')->assertSessionHasNoErrors();

        $logo = $client->fresh()->logo;
        $this->assertStringStartsWith('uploads/', $logo);
        Storage::disk('public')->assertExists($logo);
        $this->assertSame(1, \App\Models\Media::where('path', $logo)->count());

        // the logo now renders as an image and the client is linked
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertGreaterThanOrEqual(1, substr_count($html, media_url($logo)));
        $this->assertStringContainsString('href="https://northwind.example"', $html);

        // a bad website URL is rejected before anything is stored
        $this->actingAs($admin)->put("/admin/clients/{$client->id}", [
            'name' => $client->name, 'url' => 'not-a-url',
        ])->assertSessionHasErrors('url');
    }

    public function test_the_clients_section_disappears_when_there_is_nothing_real_to_show(): void
    {
        \App\Models\Client::query()->delete();
        \App\Models\Testimonial::query()->delete();

        $html = $this->get('/')->assertOk()->getContent();

        // no logos, and no heading promising endorsement that isn't there
        $this->assertStringNotContainsString('gs-client', $html);
        $this->assertStringNotContainsString(t('tstTitle', 'en'), $html);
        $this->assertStringNotContainsString(t('tstVoicesTitle', 'en'), $html);
    }

    public function test_projects_page_lists_every_project_with_category_chips(): void
    {
        $html = $this->get('/projects')->assertOk()->getContent();

        foreach (\App\Models\Project::all() as $project) {
            $this->assertStringContainsString($project->tr('title'), $html);
        }

        // one chip per taxonomy entry, plus "All", and Alpine drives the
        // selection. Industry names contain "&", so compare escaped.
        foreach (\App\Models\Category::all() as $category) {
            $this->assertStringContainsString(e($category->tr('name')), $html);
            $this->assertStringContainsString("cat === '{$category->slug}'", $html);
        }
        $this->assertStringContainsString(t('catAll', 'en'), $html);
        $this->assertStringContainsString("x-data=\"{ cat: 'all' }\"", $html);

        // the locale-prefixed variants resolve too
        $this->get('/ar/projects')->assertOk();
        $this->get('/ckb/projects')->assertOk();
    }

    public function test_category_query_string_preselects_a_chip(): void
    {
        $category = \App\Models\Category::first();

        $this->get('/projects?category='.$category->slug)
            ->assertOk()
            ->assertSee("x-data=\"{ cat: '{$category->slug}' }\"", false);

        // an unknown slug falls back to showing everything rather than nothing
        $this->get('/projects?category=nope')
            ->assertOk()
            ->assertSee("x-data=\"{ cat: 'all' }\"", false);
    }

    public function test_project_form_assigns_a_category(): void
    {
        $admin = User::first();
        $project = \App\Models\Project::first();
        $target = \App\Models\Category::where('id', '!=', $project->category_id)->first();

        $this->actingAs($admin)->put("/admin/projects/{$project->id}", [
            'title' => ['en' => 'Recategorised'],
            'category_id' => $target->id,
            'fit' => 'contain',
        ])->assertRedirect('/admin/projects')->assertSessionHasNoErrors();

        $project->refresh();
        $this->assertSame($target->id, $project->category_id);
        $this->assertSame('contain', $project->fit);
        $this->assertSame($target->tr('name'), $project->category->tr('name'));

        // a category that does not exist is rejected
        $this->actingAs($admin)->put("/admin/projects/{$project->id}", [
            'title' => ['en' => 'x'], 'category_id' => 99999,
        ])->assertSessionHasErrors('category_id');
    }

    public function test_deleting_a_category_leaves_its_projects_uncategorised(): void
    {
        $admin = User::first();
        // pick a type that actually has projects — industries are seeded empty
        $project = \App\Models\Project::whereNotNull('category_id')->firstOrFail();
        $category = $project->category;

        $this->actingAs($admin)->delete("/admin/categories/{$category->id}")
            ->assertRedirect('/admin/categories');

        $this->assertNull($project->fresh()->category_id);
        $this->get('/projects')->assertOk();   // page still renders without a category
        $this->get('/')->assertOk();
    }

    public function test_category_slug_is_generated_and_unique(): void
    {
        $admin = User::first();

        $this->actingAs($admin)->post('/admin/categories', [
            'name' => ['en' => 'Data Platforms'],
        ])->assertRedirect('/admin/categories');

        $this->assertSame('data-platforms', \App\Models\Category::latest('id')->first()->slug);

        // slug collisions are rejected rather than silently overwriting
        $this->actingAs($admin)->post('/admin/categories', [
            'name' => ['en' => 'Another'], 'slug' => 'data-platforms',
        ])->assertSessionHasErrors('slug');
    }

    public function test_project_image_fit_controls_how_the_tile_renders(): void
    {
        $project = \App\Models\Project::first();
        // no stock imagery is seeded any more, so give the tile a real one
        $project->update(['image' => 'uploads/2026/08/shot.jpg']);

        $project->update(['fit' => 'cover']);
        $html = $this->get('/projects')->assertOk()->getContent();
        $this->assertStringContainsString('center/cover no-repeat', $html);

        $project->update(['fit' => 'contain']);
        $html = $this->get('/projects')->assertOk()->getContent();
        // contained logos sit on white, with no dark scrim smudging the ground
        $this->assertStringContainsString('gs-proj-fit', $html);
        $this->assertStringContainsString('gs-proj-media', $html);

        // tiles are square whichever fit is used
        $this->assertStringContainsString('aspect-ratio: 1 / 1', $html);
    }

    public function test_project_link_makes_the_tile_open_in_a_new_tab(): void
    {
        $admin = User::first();
        $project = \App\Models\Project::first();

        // no link: the tile is a plain div
        $project->update(['url' => null]);
        $html = $this->get('/projects')->assertOk()->getContent();
        $this->assertStringNotContainsString('gs-proj is-linked', $html);

        $this->actingAs($admin)->put("/admin/projects/{$project->id}", [
            'title' => ['en' => $project->tr('title')],
            'url' => 'https://powerorbits.com',
        ])->assertRedirect('/admin/projects')->assertSessionHasNoErrors();

        $html = $this->get('/projects')->assertOk()->getContent();
        $this->assertStringContainsString('gs-proj is-linked', $html);
        $this->assertStringContainsString('href="https://powerorbits.com"', $html);
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString('rel="noopener"', $html);

        // the same tile links from the home page grid too
        $this->assertStringContainsString('https://powerorbits.com', $this->get('/')->getContent());

        // a malformed link is rejected rather than rendered
        $this->actingAs($admin)->put("/admin/projects/{$project->id}", [
            'title' => ['en' => 'x'], 'url' => 'not-a-url',
        ])->assertSessionHasErrors('url');
    }

    public function test_settings_page_saves_ceo_name_address_and_map(): void
    {
        $admin = User::first();
        $sections = \App\Models\Section::all()
            ->mapWithKeys(fn ($s) => [$s->key => ['position' => $s->position, 'visible' => $s->visible ? '1' : null]])
            ->all();

        $this->actingAs($admin)->put('/admin/settings', [
            'theme_accent' => '#2ca69c', 'theme_mood' => 'midnight', 'theme_shape' => 'soft',
            'contact_phone' => '+9647517110459', 'contact_email' => 'info@gosoftware.krd',
            'contact_address' => ['en' => 'Justice Tower, Floor 16, Office 21 — Erbil', 'ar' => '', 'ckb' => ''],
            'contact_map_lat' => '36.1821139', 'contact_map_lng' => '43.9785422', 'contact_map_zoom' => 17,
            'about_ceo_name' => 'Ahmad Najm',
            'sections' => $sections,
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertSame('Ahmad Najm', gs_setting('about.ceo_name'));
        $this->assertSame('Justice Tower, Floor 16, Office 21 — Erbil', gs_setting_tr('contact.address'));
        // blank languages are dropped so the en fallback applies
        $this->assertSame(['en'], array_keys(gs_setting('contact.address')));

        // out-of-range coordinates are rejected
        $this->actingAs($admin)->put('/admin/settings', [
            'theme_accent' => '#2ca69c', 'theme_mood' => 'midnight', 'theme_shape' => 'soft',
            'contact_phone' => '+1', 'contact_email' => 'a@b.co', 'about_ceo_name' => 'X',
            'contact_map_lat' => '999', 'sections' => $sections,
        ])->assertSessionHasErrors('contact_map_lat');
    }

    public function test_contact_section_renders_address_and_map(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString(gs_setting_tr('contact.address'), $html);
        $this->assertStringContainsString(t('visitUs', 'en'), $html);
        $this->assertStringContainsString('maps.google.com/maps?', $html);
        $this->assertStringContainsString('q=36.1821139%2C43.9785422', $html);
        $this->assertStringContainsString('output=embed', $html);
        $this->assertStringContainsString('google.com/maps/dir/', $html);

        // clearing the coordinates falls back to pinning by address name
        \App\Support\Settings::set('contact.map_lat', null);
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('output=embed', $html);
        $this->assertStringContainsString(urlencode('Justice Tower'), $html);
        $this->assertStringContainsString('google.com/maps/search/', $html);

        // with no address either, the map disappears entirely
        \App\Support\Settings::set('contact.address', []);
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('output=embed', $html);
        $this->assertStringNotContainsString('gs-map', $html);
    }

    public function test_pasted_google_maps_embed_overrides_the_coordinates(): void
    {
        $iframe = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!2sJustice+Tower" '
            .'width="600" height="450" style="border:0;" allowfullscreen></iframe>';

        \App\Support\Settings::set('contact.map_embed', \App\Support\MapEmbed::custom($iframe));

        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('https://www.google.com/maps/embed?pb=!1m18!2sJustice+Tower', $html);
        // the coordinate-built embed is no longer used
        $this->assertStringNotContainsString('output=embed', $html);

        // an embed alone (no coordinates) still renders the map
        \App\Support\Settings::set('contact.map_lat', null);
        \App\Support\Settings::set('contact.map_lng', null);
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('google.com/maps/embed?pb=', $html);
        $this->assertStringContainsString('google.com/maps/search/', $html); // directions fall back to a search
    }

    public function test_non_google_map_embeds_are_rejected(): void
    {
        $bad = [
            '<iframe src="https://evil.example/maps/embed"></iframe>',
            'https://evil.example/maps',
            'http://www.google.com/maps/embed?pb=1',            // not https
            'https://www.google.com.attacker.net/maps/embed',   // lookalike host
            'https://www.google.com/search?q=maps',             // right host, wrong path
            '<script>alert(1)</script>',
        ];

        foreach ($bad as $value) {
            $this->assertNull(\App\Support\MapEmbed::custom($value), "should reject: {$value}");
        }

        $this->assertSame(
            'https://www.google.com/maps/embed?pb=xyz',
            \App\Support\MapEmbed::custom('<iframe src="https://www.google.com/maps/embed?pb=xyz"></iframe>')
        );

        // and the settings form refuses it instead of silently dropping it
        $sections = \App\Models\Section::all()
            ->mapWithKeys(fn ($s) => [$s->key => ['position' => $s->position, 'visible' => '1']])->all();

        $this->actingAs(User::first())->put('/admin/settings', [
            'theme_accent' => '#2ca69c', 'theme_mood' => 'midnight', 'theme_shape' => 'soft',
            'contact_phone' => '+1', 'contact_email' => 'a@b.co', 'about_ceo_name' => 'X',
            'contact_map_embed' => 'https://evil.example/maps', 'sections' => $sections,
        ])->assertSessionHasErrors('contact_map_embed');
    }

    public function test_a_project_without_an_image_gets_a_branded_panel(): void
    {
        \App\Models\Project::first()->update(['image' => null]);

        $html = $this->get('/projects')->assertOk()->getContent();

        $this->assertStringContainsString('gs-photo-empty', $html);
        $this->assertStringNotContainsString("background-image: url('')", $html);
    }
}
