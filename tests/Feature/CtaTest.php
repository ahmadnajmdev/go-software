<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CtaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public static function locales(): array
    {
        return [['en', '/'], ['ar', '/ar'], ['ckb', '/ckb']];
    }

    /** The variants that used to compete with the real CTA. */
    private const RETIRED = ['getQuote', 'aboutCta', 'whyCta'];

    #[DataProvider('locales')]
    public function test_one_primary_label_replaces_the_seven_variants(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $this->assertStringContainsString(e(t('ctaEstimate', $locale)), $html);

        foreach (self::RETIRED as $key) {
            $this->assertStringNotContainsString(e(t($key, $locale)), $html,
                "the old “{$key}” label is still on the page");
        }
    }

    #[DataProvider('locales')]
    public function test_the_secondary_action_is_always_whatsapp(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $this->assertStringContainsString(e(t('waTalk', $locale)), $html);
    }

    public function test_every_primary_cta_reports_the_same_english_label(): void
    {
        foreach (['/', '/ar', '/ckb', '/projects', '/services/ecommerce'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            preg_match_all('/data-gs-track="cta_click"[^>]*data-gs-label="([^"]+)"/', $html, $m);

            foreach ($m[1] as $label) {
                // "Get estimate" is the sticky bar, which has no room for the
                // full label; "Learn More" is navigation, not the primary CTA.
                $this->assertContains($label, [
                    t('ctaEstimate', 'en'), t('barEstimate', 'en'), t('learnMore', 'en'),
                ], "unexpected CTA label “{$label}” on {$url}");
            }
        }
    }

    public function test_the_about_cta_no_longer_promises_a_page_it_cannot_deliver(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // "More About Us" pointed at #contact
        $this->assertStringNotContainsString(e(t('aboutCta', 'en')), $html);
        $this->assertStringContainsString('data-gs-location="about"', $html);
    }

    public function test_the_why_cta_no_longer_scrolls_the_visitor_backwards(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        $why = substr($html, strpos($html, 'data-gs-location="why"') - 400, 600);

        // "Discover More" sent people back up the page to #about
        $this->assertStringNotContainsString('#about', $why);
        $this->assertStringContainsString('#contact', $why);
    }

    #[DataProvider('locales')]
    public function test_the_form_button_asks_for_an_estimate(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $this->assertStringContainsString(e(t('sendMsg', $locale)), $html);
        $this->assertSame('Get My Free Estimate', t('sendMsg', 'en'));
    }

    public function test_footer_service_links_reach_the_service_pages(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        $footer = substr($html, strpos($html, '<footer'));

        foreach (ServicePagesTest::SLUGS as $slug) {
            $this->assertStringContainsString(url("/services/{$slug}"), $footer,
                "the footer has no link to {$slug}");
        }

        // and none of them is still an anchor back to the home page section
        $this->assertStringNotContainsString('#services', $footer);
    }

    public function test_footer_service_links_stay_in_the_visitors_language(): void
    {
        $html = $this->get('/ckb')->assertOk()->getContent();
        $footer = substr($html, strpos($html, '<footer'));

        $this->assertStringContainsString(url('/ckb/services/pos-inventory'), $footer);
        $this->assertStringContainsString(e(\App\Support\ServiceCatalogue::page('pos-inventory', 'ckb')['name']), $footer);
    }
}
