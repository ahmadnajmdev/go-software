<?php

namespace Tests\Feature;

use App\Models\ContactSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertSee(t('h1TitleA', $locale));
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
        $payload = ['name' => 'Jane', 'email' => 'jane@example.com', 'message' => 'Hello there'];

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

        $this->actingAs($admin)->postJson('/admin/api/items', ['model' => 'services'])->assertOk();
        $this->assertSame(5, \App\Models\Service::count());

        $new = \App\Models\Service::orderByDesc('position')->first();
        $this->actingAs($admin)->deleteJson('/admin/api/items', ['model' => 'services', 'id' => $new->id])->assertOk();
        $this->assertSame(4, \App\Models\Service::count());
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
}
