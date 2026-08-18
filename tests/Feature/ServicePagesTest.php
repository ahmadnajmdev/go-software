<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Support\ServiceCatalogue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ServicePagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public const SLUGS = [
        'website-development', 'web-applications', 'mobile-app-development',
        'management-systems', 'pos-inventory', 'ecommerce', 'support-and-maintenance',
    ];

    public static function pages(): array
    {
        $cases = [];

        foreach (self::SLUGS as $slug) {
            foreach (['en' => '', 'ar' => '/ar', 'ckb' => '/ckb'] as $locale => $prefix) {
                $cases["{$locale} {$slug}"] = [$locale, "{$prefix}/services/{$slug}"];
            }
        }

        return $cases;
    }

    #[DataProvider('pages')]
    public function test_every_service_page_renders_in_every_language(string $locale, string $url): void
    {
        $slug = basename($url);
        $page = ServiceCatalogue::page($slug, $locale);

        $html = $this->get($url)->assertOk()->getContent();

        $this->assertStringContainsString(e($page['h1']), $html);
        $this->assertStringContainsString(e($page['title']), $html);
        $this->assertStringContainsString(e($page['meta']), $html);
        $this->assertStringContainsString(e($page['intro']), $html);
    }

    #[DataProvider('pages')]
    public function test_every_page_carries_the_full_template(string $locale, string $url): void
    {
        $slug = basename($url);
        $page = ServiceCatalogue::page($slug, $locale);
        $html = $this->get($url)->assertOk()->getContent();

        // who this is for, what you get, price block, process, FAQ, final form
        $this->assertStringContainsString(e($page['whoTitle']), $html);
        $this->assertStringContainsString(e($page['getTitle']), $html);
        $this->assertStringContainsString(e(t('svcCostTitle', $locale)), $html);
        $this->assertStringContainsString(e($page['processTitle']), $html);
        $this->assertStringContainsString(e($page['faqTitle']), $html);
        $this->assertStringContainsString('id="contact"', $html);

        foreach ($page['who'] as $item) {
            $this->assertStringContainsString(e($item), $html);
        }

        foreach ($page['faqs'] as $faq) {
            $this->assertStringContainsString(e($faq['q']), $html);
            $this->assertStringContainsString(e($faq['a']), $html);
        }

        // service-specific WhatsApp message, in this language
        $this->assertStringContainsString(rawurlencode(t(\App\Support\WhatsApp::MESSAGES[$page['whatsapp']], $locale)), $html);
    }

    public function test_titles_and_descriptions_are_unique_across_every_page(): void
    {
        $titles = [];
        $metas = [];

        foreach (self::SLUGS as $slug) {
            foreach (['en' => '', 'ar' => '/ar', 'ckb' => '/ckb'] as $prefix) {
                $html = $this->get("{$prefix}/services/{$slug}")->assertOk()->getContent();
                preg_match('#<title>(.*?)</title>#s', $html, $t);
                preg_match('#<meta name="description" content="(.*?)">#s', $html, $m);
                $titles[] = $t[1];
                $metas[] = $m[1];
            }
        }

        $this->assertCount(21, $titles);
        $this->assertSame($titles, array_unique($titles), 'two service pages share a <title>');
        $this->assertSame($metas, array_unique($metas), 'two service pages share a meta description');
    }

    public function test_the_two_missing_services_now_exist(): void
    {
        // POS and e-commerce were the highest-intent offerings in this market
        // and appeared nowhere on the site, in any language.
        foreach (['pos-inventory', 'ecommerce'] as $slug) {
            $this->assertTrue(Service::where('slug', $slug)->exists());
            $this->get("/services/{$slug}")->assertOk();
        }
    }

    public function test_the_pos_page_leads_with_working_offline(): void
    {
        $html = $this->get('/services/pos-inventory')->assertOk()->getContent();

        $this->assertStringContainsString('works offline', strtolower(strip_tags($html)));
        // it is in the headline and the title, not buried in a bullet
        $this->assertStringContainsString('offline', strtolower(ServiceCatalogue::page('pos-inventory', 'en')['h1']));
        $this->assertStringContainsString('Offline', ServiceCatalogue::page('pos-inventory', 'en')['title']);
    }

    public function test_the_ecommerce_page_promises_no_integration_we_have_not_confirmed(): void
    {
        $page = ServiceCatalogue::page('ecommerce', 'en');
        $body = strtolower(json_encode($page));

        // naming FIB/Zain Cash/Nass as built would be a claim I cannot source
        foreach (['fib', 'zain cash', 'nass wallet'] as $provider) {
            $this->assertStringNotContainsString($provider, $body,
                "the e-commerce page names {$provider} as an integration — unconfirmed, see BLOCKED.md");
        }

        $this->assertStringContainsString('cash on delivery', $body);
    }

    public function test_no_price_is_invented_anywhere(): void
    {
        foreach (self::SLUGS as $slug) {
            foreach (['en', 'ar', 'ckb'] as $locale) {
                $copy = json_encode(ServiceCatalogue::page($slug, $locale), JSON_UNESCAPED_UNICODE);

                $this->assertDoesNotMatchRegularExpression('/\$\s?\d/', $copy,
                    "the {$locale} copy for {$slug} quotes a price");
            }

            // and the cost section itself names no figure. (The contact form's
            // budget selector does contain ranges — those are what the visitor
            // chooses from, not what we charge.)
            $html = $this->get("/services/{$slug}")->assertOk()->getContent();
            $start = strpos($html, e(t('svcCostTitle', 'en')));
            $this->assertNotFalse($start, "{$slug} has no cost section");
            $costSection = substr($html, $start, strpos($html, '</section>', $start) - $start);

            $this->assertDoesNotMatchRegularExpression('/\$\s?\d/', strip_tags($costSection),
                "{$slug} shows an invented price in its cost section");
        }
    }

    public function test_the_home_page_cards_lead_with_the_outcome_and_link_to_the_page(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        foreach (self::SLUGS as $slug) {
            $this->assertStringContainsString(url("/services/{$slug}"), $html, "no card links to {$slug}");
            $this->assertStringContainsString(e(ServiceCatalogue::page($slug, 'en')['h1']), $html);
        }

        // and no card still dead-ends at the contact anchor
        $this->assertStringNotContainsString('data-gs-location="service_card" data-gs-service="Website Development"', $html);
    }

    public function test_an_unknown_service_is_a_404(): void
    {
        $this->get('/services/nope')->assertNotFound();
        $this->get('/ckb/services/nope')->assertNotFound();
    }

    public function test_pages_state_who_owns_the_code(): void
    {
        foreach (['en' => '', 'ckb' => '/ckb'] as $locale => $prefix) {
            $html = $this->get("{$prefix}/services/management-systems")->assertOk()->getContent();
            $this->assertStringContainsString(e(t('ownCode', $locale)), $html);
        }
    }
}
