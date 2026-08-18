<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_every_figure_comes_from_the_one_config_file(): void
    {
        config([
            'stats.projects_delivered' => 42,
            'stats.happy_clients' => 21,
            'stats.years_in_software' => 7,
            'stats.satisfaction_rate' => 91,
        ]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('data-count="42"', $html);
        $this->assertStringContainsString('data-count="21"', $html);
        $this->assertStringContainsString('data-count="91"', $html);

        // the About badge read a hardcoded "15+" and now tracks the same number
        $this->assertStringContainsString('data-count="7"', $html);
        $this->assertStringContainsString('>7+<', $html);
        $this->assertStringNotContainsString('>15+<', $html);
    }

    public function test_no_second_copy_of_the_numbers_survives_in_settings(): void
    {
        // a settings row would silently disagree with the config file
        $this->assertSame(0, Setting::where('key', 'stats.values')->count());
        $this->assertNull(gs_setting('stats.values'));
    }

    public function test_the_award_badge_is_hidden_until_someone_says_who_gave_it(): void
    {
        config(['stats.award.awarded_by' => null]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString(t('topRated', 'en'), $html);
        $this->assertStringNotContainsString(t('agency2025', 'en'), $html);
    }

    public function test_naming_the_awarding_body_brings_the_badge_back(): void
    {
        config(['stats.award.awarded_by' => 'Clutch']);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString(t('topRated', 'en'), $html);
        $this->assertStringContainsString('Clutch', $html);
    }

    public function test_the_badge_names_its_source_in_every_language(): void
    {
        config(['stats.award.awarded_by' => 'Clutch']);

        foreach (['en' => '/', 'ar' => '/ar', 'ckb' => '/ckb'] as $locale => $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertStringContainsString(e(t('topRated', $locale)), $html);
            $this->assertStringContainsString('Clutch', $html);
        }
    }
}
