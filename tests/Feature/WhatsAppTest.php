<?php

namespace Tests\Feature;

use App\Support\Settings;
use App\Support\WhatsApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class WhatsAppTest extends TestCase
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

    public function test_the_number_comes_from_the_phone_shown_on_the_site(): void
    {
        // one source of truth: "+9647517110459" → "9647517110459"
        $this->assertSame('9647517110459', WhatsApp::number());
        $this->assertStringStartsWith('https://wa.me/9647517110459', WhatsApp::url('hi'));
    }

    public function test_a_separate_whatsapp_number_overrides_the_phone(): void
    {
        Settings::set('whatsapp.number', '+964 750 000 0000');

        $this->assertSame('9647500000000', WhatsApp::number());
    }

    public function test_nothing_renders_when_no_number_is_configured(): void
    {
        Settings::set('contact.phone', '');
        Settings::set('whatsapp.number', null);

        $this->assertFalse(WhatsApp::isConfigured());
        $this->assertNull(WhatsApp::url('hi'));

        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('wa.me/', $html);
    }

    #[DataProvider('locales')]
    public function test_the_message_is_pre_written_in_the_visitors_language(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        preg_match_all('#wa\.me/\d+\?text=([^"]+)#', $html, $matches);
        $this->assertNotEmpty($matches[1], 'no WhatsApp links on '.$url);

        $messages = array_map('rawurldecode', $matches[1]);

        // the hero message, in this locale, is one of them
        $this->assertContains(t('waMsgHero', $locale), $messages);
        $this->assertContains(t('waMsgContact', $locale), $messages);

        // and none of them is the raw key
        foreach ($messages as $message) {
            $this->assertStringNotContainsString('waMsg', $message);
        }
    }

    public function test_each_service_card_carries_its_own_message(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        $messages = array_map('rawurldecode', $this->whatsappMessages($html));

        foreach (['waMsgWebsite', 'waMsgWebApp', 'waMsgMobile', 'waMsgSystem'] as $key) {
            $this->assertContains(t($key, 'en'), $messages, "the {$key} card message is missing");
        }
    }

    public function test_links_open_in_a_new_tab_and_report_their_source(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('data-gs-track="whatsapp_click"', $html);
        $this->assertStringContainsString('data-gs-source="hero"', $html);
        $this->assertStringContainsString('data-gs-source="contact"', $html);
        $this->assertStringContainsString('data-gs-source="sticky_bar"', $html);

        // every wa.me link is target=_blank rel=noopener
        preg_match_all('#<a[^>]+wa\.me[^>]*>#', $html, $anchors);
        $this->assertNotEmpty($anchors[0]);

        foreach ($anchors[0] as $anchor) {
            $this->assertStringContainsString('target="_blank"', $anchor);
            $this->assertStringContainsString('rel="noopener"', $anchor);
        }
    }

    public function test_whatsapp_is_reachable_from_the_hero_the_contact_section_and_the_sticky_bar(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertGreaterThanOrEqual(3, substr_count($html, 'wa.me/'));
        $this->assertStringContainsString('id="gs-sticky"', $html);
    }

    #[DataProvider('locales')]
    public function test_the_sticky_bar_offers_whatsapp_call_and_an_estimate(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $bar = $this->stickyBar($html);

        $this->assertStringContainsString('data-gs-source="sticky_bar"', $bar);
        $this->assertStringContainsString('href="tel:'.gs_setting('contact.phone').'"', $bar);
        $this->assertStringContainsString('#contact', $bar);
        $this->assertStringContainsString('data-gs-location="sticky_bar"', $bar);

        // labels in the visitor's language, not raw keys
        $this->assertStringContainsString(e(t('barCall', $locale)), $bar);
        $this->assertStringContainsString(e(t('barEstimate', $locale)), $bar);
        $this->assertStringNotContainsString('barCall', strip_tags($bar));
    }

    public function test_the_sticky_bar_reports_a_stable_english_cta_label(): void
    {
        $bar = $this->stickyBar($this->get('/ckb')->assertOk()->getContent());

        $this->assertStringContainsString('data-gs-label="'.e(t('barEstimate', 'en')).'"', $bar);
    }

    private function whatsappMessages(string $html): array
    {
        preg_match_all('#wa\.me/\d+\?text=([^"]+)#', $html, $m);

        return $m[1];
    }

    private function stickyBar(string $html): string
    {
        $start = strpos($html, '<div class="gs-sticky" id="gs-sticky">');
        $this->assertNotFalse($start, 'the sticky bar is not rendered');

        return substr($html, $start, strpos($html, '</div>', $start) - $start + 6);
    }
}
