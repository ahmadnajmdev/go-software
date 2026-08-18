<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrustTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_the_legal_entity_line_is_hidden_until_it_is_supplied(): void
    {
        config(['company.legal_name' => null, 'company.registration_number' => null]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString(t('ftRegistered', 'en'), $html);
    }

    public function test_the_legal_entity_appears_in_the_footer_once_configured(): void
    {
        config([
            'company.legal_name' => 'GoSoftware for Information Technology Ltd.',
            'company.registration_number' => 'ERB-12345',
        ]);

        $html = $this->get('/')->assertOk()->getContent();
        $footer = substr($html, strpos($html, '<footer'));

        $this->assertStringContainsString('GoSoftware for Information Technology Ltd.', $footer);
        $this->assertStringContainsString('ERB-12345', $footer);
    }

    public function test_no_warranty_is_claimed_until_confirmed(): void
    {
        config(['company.warranty_days' => null]);

        foreach (ServicePagesTest::SLUGS as $slug) {
            $html = $this->get("/services/{$slug}")->assertOk()->getContent();
            $this->assertStringNotContainsString('free bug fixes', $html);
        }
    }

    public function test_the_warranty_statement_appears_once_a_period_is_set(): void
    {
        config(['company.warranty_days' => 30]);

        $html = $this->get('/services/management-systems')->assertOk()->getContent();
        $this->assertStringContainsString('30 days of free bug fixes after launch.', $html);

        // and in the visitor's language
        $html = $this->get('/ckb/services/management-systems')->assertOk()->getContent();
        $this->assertStringContainsString('30 '.mb_substr(t('warrantyLine', 'ckb'), 6), $html);
    }

    public function test_support_tiers_are_hidden_until_response_times_exist(): void
    {
        config(['company.support_tiers' => []]);

        $html = $this->get('/services/support-and-maintenance')->assertOk()->getContent();
        $this->assertStringNotContainsString(t('supportTiersTitle', 'en'), $html);
    }

    public function test_support_tiers_publish_their_response_times(): void
    {
        config(['company.support_tiers' => [
            ['name' => 'Standard', 'price' => '$200 / month', 'response' => '1 working day',
                'includes' => ['Bug fixes', 'Security updates']],
            ['name' => 'Priority', 'price' => '$500 / month', 'response' => '4 working hours',
                'includes' => ['Everything in Standard', 'Phone escalation']],
        ]]);

        $html = $this->get('/services/support-and-maintenance')->assertOk()->getContent();

        $this->assertStringContainsString(t('supportTiersTitle', 'en'), $html);
        $this->assertStringContainsString('1 working day', $html);
        $this->assertStringContainsString('4 working hours', $html);
        $this->assertStringContainsString('Phone escalation', $html);
    }

    public function test_every_service_page_states_who_owns_the_code(): void
    {
        // no local competitor says this
        foreach (ServicePagesTest::SLUGS as $slug) {
            foreach (['en' => '', 'ar' => '/ar', 'ckb' => '/ckb'] as $locale => $prefix) {
                $this->get("{$prefix}/services/{$slug}")
                    ->assertOk()
                    ->assertSee(t('ownCode', $locale), false);
            }
        }
    }

    public function test_the_faq_answers_the_ownership_question_too(): void
    {
        foreach (['en', 'ar', 'ckb'] as $locale) {
            $faqs = (require resource_path('faq.php'))[$locale];
            $answers = implode(' ', array_column($faqs, 'a'));

            $this->assertNotEmpty($answers);
        }

        $en = implode(' ', array_column((require resource_path('faq.php'))['en'], 'a'));
        $this->assertStringContainsString('full source plus all credentials are handed over at launch', $en);
    }
}
