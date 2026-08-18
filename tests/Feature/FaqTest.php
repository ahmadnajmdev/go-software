<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FaqTest extends TestCase
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

    private function faqs(string $locale): array
    {
        return (require resource_path('faq.php'))[$locale];
    }

    #[DataProvider('locales')]
    public function test_all_eight_questions_and_answers_render(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $this->assertCount(8, $this->faqs($locale));

        foreach ($this->faqs($locale) as $faq) {
            $this->assertStringContainsString(e($faq['q']), $html);
            $this->assertStringContainsString(e($faq['a']), $html);
        }
    }

    #[DataProvider('locales')]
    public function test_the_accordion_is_semantic_and_reports_which_question_opened(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        // native details/summary: keyboard and screen-reader correct with no JS
        $this->assertSame(8, substr_count($html, '<details class="gs-faq"'));
        $this->assertSame(8, substr_count($html, '<summary>'));

        foreach ($this->faqs($locale) as $faq) {
            $this->assertStringContainsString('data-gs-question="'.e($faq['q']).'"', $html);
        }
    }

    #[DataProvider('locales')]
    public function test_faqpage_schema_matches_what_the_page_shows(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $blocks);
        $faqSchema = null;

        foreach ($blocks[1] as $json) {
            $decoded = json_decode($json, true);
            if (($decoded['@type'] ?? null) === 'FAQPage') {
                $faqSchema = $decoded;
            }
        }

        $this->assertNotNull($faqSchema, 'no FAQPage schema on '.$url);
        $this->assertCount(8, $faqSchema['mainEntity']);

        foreach ($this->faqs($locale) as $i => $faq) {
            $this->assertSame($faq['q'], $faqSchema['mainEntity'][$i]['name']);
            $this->assertSame($faq['a'], $faqSchema['mainEntity'][$i]['acceptedAnswer']['text']);
        }
    }

    public function test_the_faq_sits_above_the_final_cta(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // objections have to be answered before the form, not after it
        $this->assertLessThan(
            strpos($html, 'data-section="contact"'),
            strpos($html, 'data-section="faq"')
        );
    }

    public function test_no_unconfirmed_payment_integration_is_claimed(): void
    {
        foreach (['en', 'ar', 'ckb'] as $locale) {
            $body = strtolower(json_encode($this->faqs($locale), JSON_UNESCAPED_UNICODE));

            foreach (['fib', 'zain cash', 'nass wallet', 'زين كاش', 'فيب'] as $provider) {
                $this->assertStringNotContainsString($provider, $body,
                    "the FAQ claims a {$provider} integration — unconfirmed, see BLOCKED.md");
            }
        }

        // cash on delivery is real and is named
        $this->assertStringContainsString('cash on delivery', strtolower(json_encode($this->faqs('en'))));
    }

    public function test_no_warranty_period_is_invented(): void
    {
        // "30 days of free bug fixes" needs confirming before it is published
        foreach (['en', 'ar', 'ckb'] as $locale) {
            $body = json_encode($this->faqs($locale), JSON_UNESCAPED_UNICODE);
            $this->assertDoesNotMatchRegularExpression('/\b\d+\s*(days?|يوم|ڕۆژ)\b.*(free|bug|مجان|خۆڕایی)/iu', $body);
        }
    }

    public function test_service_pages_carry_their_own_faq_schema(): void
    {
        $html = $this->get('/services/pos-inventory')->assertOk()->getContent();

        $this->assertStringContainsString('"@type":"FAQPage"', $html);
        $this->assertStringContainsString('data-gs-question=', $html);
    }
}
