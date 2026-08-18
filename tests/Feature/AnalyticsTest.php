<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AnalyticsTest extends TestCase
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

    private function path(string $locale): string
    {
        return $locale === 'en' ? '/' : '/'.$locale;
    }

    public function test_no_tracking_code_without_a_container_id(): void
    {
        config(['analytics.gtm_id' => null]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('googletagmanager.com', $html);
    }

    #[DataProvider('locales')]
    public function test_container_loads_in_every_locale(string $locale): void
    {
        config(['analytics.gtm_id' => 'GTM-ABC1234']);

        $html = $this->get($this->path($locale))->assertOk()->getContent();

        $this->assertStringContainsString('googletagmanager.com/gtm.js', $html);
        $this->assertStringContainsString('"GTM-ABC1234"', $html);
        // the noscript fallback goes immediately after <body>
        $this->assertStringContainsString('googletagmanager.com/ns.html?id=GTM-ABC1234', $html);
    }

    public function test_do_not_track_is_honoured_before_the_container_loads(): void
    {
        config(['analytics.gtm_id' => 'GTM-ABC1234']);

        $html = $this->get('/')->assertOk()->getContent();

        // the loader checks DNT and bails out before injecting the container,
        // so no vendor tag inside it can fire either
        $this->assertStringContainsString('navigator.doNotTrack', $html);
        $this->assertStringContainsString('w.gsNoTrack = true', $html);
    }

    public function test_a_malformed_container_id_is_never_interpolated(): void
    {
        config(['analytics.gtm_id' => '"); alert(1); //']);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('alert(1)', $html);
        $this->assertStringNotContainsString('googletagmanager.com', $html);
    }

    public function test_hero_ctas_report_a_stable_english_label(): void
    {
        $html = $this->get('/ckb')->assertOk()->getContent();

        $this->assertStringContainsString('data-gs-track="cta_click"', $html);
        $this->assertStringContainsString('data-gs-location="hero"', $html);

        // the label stays English on the Kurdish page so one CTA aggregates
        // into one row rather than splitting three ways
        $this->assertStringContainsString('data-gs-label="'.e(t('ctaEstimate', 'en')).'"', $html);
        $this->assertStringNotContainsString('data-gs-label="'.e(t('ctaEstimate', 'ckb')).'"', $html);
    }

    public function test_service_cards_and_the_contact_form_are_instrumented(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('data-gs-location="service_card"', $html);
        $this->assertStringContainsString('data-gs-service="'.e(\App\Models\Service::first()->tr('title', 'en')).'"', $html);

        // form_start is bound from this attribute
        $this->assertStringContainsString('data-gs-form="contact"', $html);
    }

    public function test_linked_projects_report_a_project_view(): void
    {
        $project = \App\Models\Project::first();

        $project->update(['url' => null]);
        $html = $this->get('/projects')->assertOk()->getContent();
        $this->assertStringNotContainsString('data-gs-project="'.e($project->tr('title', 'en')).'"', $html);

        $project->update(['url' => 'https://powerorbits.com']);
        $html = $this->get('/projects')->assertOk()->getContent();
        $this->assertStringContainsString('data-gs-track="project_view"', $html);
        $this->assertStringContainsString('data-gs-project="'.e($project->tr('title', 'en')).'"', $html);
    }

    public function test_phone_and_email_links_exist_for_the_href_based_listeners(): void
    {
        // analytics.js infers phone_click / email_click from the href, so the
        // contract these events depend on is the link shape itself
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('href="tel:'.gs_setting('contact.phone').'"', $html);
        $this->assertStringContainsString('href="mailto:'.gs_setting('contact.email').'"', $html);
    }
}
