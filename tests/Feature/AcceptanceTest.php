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
}
