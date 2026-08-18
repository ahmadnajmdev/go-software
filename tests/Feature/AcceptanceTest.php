<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * The CRO acceptance checklist, as a test. Each case is a thing that must not
 * come back — a claim, a dead link, an untranslated key, a stock photo.
 */
class AcceptanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** Every public page, in every language. */
    public static function pages(): array
    {
        $cases = [];

        foreach (['en', 'ar', 'ckb'] as $locale) {
            $prefix = $locale === 'en' ? '' : '/'.$locale;

            foreach (['', '/projects', '/privacy-policy', '/terms-of-service'] as $path) {
                $url = ($prefix.$path) ?: '/';
                $cases["{$locale}{$path}"] = [$url];
            }
        }

        return $cases;
    }

    #[DataProvider('pages')]
    public function test_nothing_claims_the_company_is_in_the_uk(string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        foreach (['UK-based', 'UK software', 'United Kingdom', 'Manchester', 'British',
            'بەریتانی', 'شانشینی یەکگرتوو', 'بريطاني', 'المملكة المتحدة', 'مانچێستەر'] as $banned) {
            $this->assertStringNotContainsString($banned, $html, "{$url} still says “{$banned}”");
        }
    }

    #[DataProvider('pages')]
    public function test_the_erbil_location_is_stated(string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $this->assertTrue(
            str_contains($html, 'Erbil, Justice Tower')
            || str_contains($html, 'برج العدالة')
            || str_contains($html, 'تاوەری عەدالە'),
            "{$url} does not say where the company is"
        );
    }

    public function test_the_hero_eyebrow_places_the_company_in_erbil(): void
    {
        $this->get('/')->assertOk()->assertSee('CUSTOM SOFTWARE · ERBIL, KURDISTAN', false);
        $this->get('/ar')->assertOk()->assertSee('أربيل، كردستان', false);
        $this->get('/ckb')->assertOk()->assertSee('هەولێر، کوردستان', false);
    }

    #[DataProvider('pages')]
    public function test_no_dead_links(string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $this->assertStringNotContainsString('href="#"', $html, "{$url} still has a dead link");
    }

    #[DataProvider('pages')]
    public function test_the_legal_links_in_the_footer_resolve(string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();
        $prefix = str_starts_with($url, '/ar') ? '/ar' : (str_starts_with($url, '/ckb') ? '/ckb' : '');

        $this->assertStringContainsString(url($prefix.'/privacy-policy'), $html);
        $this->assertStringContainsString(url($prefix.'/terms-of-service'), $html);
    }

    public function test_legal_pages_render_in_the_right_language(): void
    {
        $this->get('/privacy-policy')->assertOk()->assertSee('Privacy Policy');
        $this->get('/ar/privacy-policy')->assertOk()->assertSee('سياسة الخصوصية', false);
        $this->get('/ckb/privacy-policy')->assertOk()->assertSee('سیاسەتی تایبەتمەندی', false);

        $this->get('/terms-of-service')->assertOk()->assertSee('Terms of Service');
        $this->get('/ar/terms-of-service')->assertOk()->assertSee('شروط الخدمة', false);
        $this->get('/ckb/terms-of-service')->assertOk()->assertSee('مەرجەکانی خزمەتگوزاری', false);
    }

    public function test_an_unconfigured_social_channel_is_not_rendered(): void
    {
        // nothing configured: no icons anywhere, and no "Follow us" label
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('aria-label="Facebook"', $html);
        $this->assertStringNotContainsString(t('followUs', 'en'), $html);

        \App\Support\Settings::set('social.facebook', 'https://facebook.com/gosoftware');
        \App\Support\Settings::set('social.instagram', 'https://instagram.com/gosoftware');

        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('href="https://facebook.com/gosoftware"', $html);
        $this->assertStringContainsString('aria-label="Instagram"', $html);
        // the ones still unset stay absent
        $this->assertStringNotContainsString('aria-label="YouTube"', $html);
        $this->assertStringNotContainsString('href="#"', $html);
    }

    public function test_a_placeholder_url_is_treated_as_unconfigured(): void
    {
        foreach (['#', '', 'javascript:alert(1)', 'not-a-url'] as $junk) {
            \App\Support\Settings::set('social.linkedin', $junk);

            $html = $this->get('/')->assertOk()->getContent();
            $this->assertStringNotContainsString('aria-label="LinkedIn"', $html, "“{$junk}” rendered an icon");
        }
    }

    public function test_the_careers_link_is_gone(): void
    {
        $this->get('/')->assertOk()->assertDontSee(t('ftCareers', 'en'));
    }

    /** Every key referenced by a view, scraped from the Blade source. */
    private function keysUsedInViews(): array
    {
        $used = [];

        foreach (\Illuminate\Support\Facades\File::allFiles(resource_path('views')) as $file) {
            $src = $file->getContents();
            preg_match_all('/<x-t\s+k="([A-Za-z0-9_]+)"/', $src, $component);
            preg_match_all("/\\bt\\(\\s*'([A-Za-z0-9_]+)'/", $src, $helper);

            foreach (array_merge($component[1], $helper[1]) as $key) {
                $used[$key] = true;
            }
        }

        return array_keys($used);
    }

    public function test_every_key_a_view_uses_exists_in_all_three_languages(): void
    {
        // t() falls back to returning the key name, so a key missing here is a
        // raw camelCase string rendering on the page.
        $strings = \App\Models\UiString::pluck('value', 'key')->all();

        foreach ($this->keysUsedInViews() as $key) {
            $this->assertArrayHasKey($key, $strings, "t('{$key}') has no row — it renders as the raw key");

            foreach (['en', 'ar', 'ckb'] as $locale) {
                $this->assertNotEmpty($strings[$key][$locale] ?? null, "{$key} has no {$locale} translation");
            }
        }
    }

    #[DataProvider('pages')]
    public function test_no_raw_translation_key_renders_as_visible_text(string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)));

        foreach ($this->keysUsedInViews() as $key) {
            // Only camelCase keys are checked. Single lowercase keys like
            // "privacy" and "terms" are ordinary English words that legitimately
            // appear in prose — a missing one of those is caught instead by
            // test_every_key_a_view_uses_exists_in_all_three_languages.
            if (! preg_match('/[a-z][A-Z]/', $key)) {
                continue;
            }

            // a key only counts as leaked if it appears as a standalone word
            $this->assertDoesNotMatchRegularExpression(
                '/(?<![A-Za-z0-9_])'.preg_quote($key, '/').'(?![A-Za-z0-9_])/',
                $text,
                "{$url} renders the raw key “{$key}” as visible text"
            );
        }
    }

    public function test_a_key_missing_from_an_install_is_repaired_by_the_sync(): void
    {
        \App\Models\UiString::whereIn('key', ['catAll', 'projNone', 'visitUs', 'getDirections'])->delete();
        \Illuminate\Support\Facades\Cache::forget('gs.strings');

        // this is exactly what the live site was doing
        $this->assertSame('catAll', t('catAll', 'en'));

        $created = \App\Support\UiStringDefaults::syncMissing();

        $this->assertEqualsCanonicalizing(['catAll', 'projNone', 'visitUs', 'getDirections'], $created);
        $this->assertSame('All', t('catAll', 'en'));
        $this->assertSame('هەموو', t('catAll', 'ckb'));
        $this->assertSame('الكل', t('catAll', 'ar'));
    }

    public function test_the_sync_never_overwrites_copy_edited_in_the_admin_panel(): void
    {
        \App\Models\UiString::where('key', 'catAll')->update(['value' => ['en' => 'Everything']]);
        \Illuminate\Support\Facades\Cache::forget('gs.strings');

        \App\Support\UiStringDefaults::syncMissing();

        $this->assertSame('Everything', t('catAll', 'en'));
    }

    public function test_the_hero_is_one_static_message_not_a_carousel(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('heroCarousel', $html);
        // exactly one H1, and it is the one headline
        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertStringContainsString(e(t('heroTitle', 'en')), $html);
        $this->assertStringNotContainsString(e(t('h2TitleA', 'en')), $html);
    }

    public function test_the_hero_headline_reads_naturally_in_each_language(): void
    {
        // written for those readers, not transliterated: the Excel/WhatsApp
        // recognition has to land the same way
        $this->get('/')->assertOk()->assertSee('Excel and WhatsApp groups', false);
        $this->get('/ar')->assertOk()->assertSee('إكسل ومجموعات واتساب', false);
        $this->get('/ckb')->assertOk()->assertSee('ئێکسڵ و گرووپی واتسئەپ', false);
    }

    public function test_the_hero_offers_both_ctas_and_the_three_trust_lines(): void
    {
        foreach (['en' => '/', 'ar' => '/ar', 'ckb' => '/ckb'] as $locale => $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertStringContainsString(e(t('ctaEstimate', $locale)), $html);
            $this->assertStringContainsString(e(t('waTalk', $locale)), $html);

            foreach (['heroTrust1', 'heroTrust2', 'heroTrust3'] as $trust) {
                $this->assertStringContainsString(e(t($trust, $locale)), $html);
            }
        }
    }

    public function test_the_hero_image_is_sized_and_never_lazy_loaded(): void
    {
        \App\Support\Settings::set('images.hero', 'uploads/2026/08/office.jpg');

        $html = $this->get('/')->assertOk()->getContent();
        $hero = substr($html, strpos($html, 'gs-hero-media'), 700);

        // explicit dimensions so it reserves its space and adds nothing to CLS
        $this->assertMatchesRegularExpression('/width="\d+"\s+height="\d+"/', $hero);
        // it is the LCP element
        $this->assertStringContainsString('fetchpriority="high"', $hero);
        $this->assertStringNotContainsString('loading="lazy"', $hero);
    }

    #[DataProvider('pages')]
    public function test_no_stock_photography(string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        foreach (['unsplash.com', 'pexels.com', 'pixabay.com'] as $stock) {
            $this->assertStringNotContainsString($stock, $html, "{$url} still serves stock photography");
        }
    }

    #[DataProvider('pages')]
    public function test_no_image_is_hot_linked_from_a_third_party(string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        preg_match_all('#(?:<img[^>]+src|background-image:\s*url\()\([\'"]?|<img[^>]+src="([^"]+)"#i', $html, $_);
        preg_match_all('#<img[^>]+src="(https?://[^"]+)"#i', $html, $tags);
        preg_match_all("#background-image:\s*url\('(https?://[^']+)'#i", $html, $backgrounds);

        foreach (array_merge($tags[1], $backgrounds[1]) as $src) {
            $host = parse_url($src, PHP_URL_HOST);
            $this->assertContains($host, ['gosoftware.test', 'localhost', parse_url(config('app.url'), PHP_URL_HOST)],
                "{$url} hot-links an image from {$host}");
        }
    }

    public function test_a_missing_photo_renders_a_branded_panel_not_a_stranger(): void
    {
        \App\Support\Settings::set('images.about_main', null);

        $html = $this->get('/')->assertOk()->getContent();

        // a designed gap, not a broken image and not a stranger's face
        $this->assertStringNotContainsString('<img src=""', $html);
        $this->assertStringNotContainsString("background-image: url('')", $html);
        $this->assertStringContainsString('role="img"', $html);
    }

    public function test_a_real_photo_is_used_the_moment_one_is_uploaded(): void
    {
        \App\Support\Settings::set('images.about_main', 'uploads/2026/08/real-office.jpg');

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('real-office.jpg', $html);
    }

    public function test_the_newsletter_signup_is_gone(): void
    {
        // wrong goal for a B2B agency site; it competed with the estimate CTA
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString(t('ftNews', 'en'), $html);
        $this->assertStringNotContainsString(t('ftNewsBody', 'en'), $html);
        $this->assertStringNotContainsString(t('phYourEmail', 'en'), $html);
    }

    public function test_no_marquee_animates_behind_the_fold(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('gs-marquee-track', $html);
        // the technology strip duplicated every label into the DOM twice
        $this->assertStringNotContainsString(t('mq1', 'en'), $html);
    }

    #[DataProvider('pages')]
    public function test_the_nav_and_logo_appear_once_each(string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        // one <nav>, and each link written once rather than once per layout
        $this->assertSame(1, substr_count($html, '<nav id="gs-nav"'));

        $start = strpos($html, '<nav id="gs-nav"');
        $nav = substr($html, $start, strpos($html, '</nav>', $start) - $start);
        $this->assertSame(5, substr_count($nav, 'class="gs-nav-link'), 'nav links are duplicated');

        // the logo used to render again inside the mobile drawer
        $body = substr($html, strpos($html, '<body'));
        $this->assertSame(1, substr_count($body, 'logo-dark'));
    }

    public function test_client_logos_render_once_not_twice(): void
    {
        $client = \App\Models\Client::ordered()->first();
        $client->update(['logo' => 'uploads/2026/08/logo.png']);

        $html = $this->get('/')->assertOk()->getContent();

        // the marquee used to duplicate the whole strip for its scroll loop.
        // one in the hero trust row, one in the clients section.
        $this->assertSame(2, substr_count($html, media_url($client->logo)));
    }
}
