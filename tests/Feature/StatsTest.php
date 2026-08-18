<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StatsTest extends TestCase
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

    #[DataProvider('locales')]
    public function test_no_figure_is_claimed_anywhere(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        // strip scripts and styles — only what a visitor reads counts
        $visible = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', ' ', $html);
        $text = preg_replace('/\s+/', ' ', strip_tags($visible));

        // the budget selector lists ranges the visitor picks; not our claim
        $text = str_replace(
            array_map(fn ($k) => t($k, $locale), ['bud1', 'bud2', 'bud3', 'bud4']),
            ' ',
            $text
        );

        $this->assertDoesNotMatchRegularExpression('/\b\d{2,}\s*\+/', $text,
            "{$url} claims an unsourced figure");
        $this->assertDoesNotMatchRegularExpression('/\b\d{1,3}\s*%/', $text,
            "{$url} claims an unsourced percentage");
    }

    public function test_the_stats_band_does_not_render_at_all(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // an empty teal band would be worse than no band
        $this->assertStringNotContainsString('gs-count', $html);
        $this->assertStringNotContainsString(t('st1', 'en'), $html);
        $this->assertStringNotContainsString(t('st4', 'en'), $html);
    }

    public function test_the_about_photo_badge_is_gone(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString(t('yearsIn', 'en'), $html);
        $this->assertStringNotContainsString('>15+<', $html);
    }

    public function test_the_ninety_eight_percent_claim_is_out_of_the_copy(): void
    {
        // it also lived in the Why GoSoftware prose, where no config switch
        // would have reached it
        foreach (['en', 'ar', 'ckb'] as $locale) {
            $this->assertStringNotContainsString('98', t('why2D', $locale));
        }

        $this->assertStringContainsString('Fixed scope and price', t('why2D', 'en'));
    }

    public function test_a_supplied_figure_comes_straight_back(): void
    {
        config(['stats.projects_delivered' => 42, 'stats.satisfaction_rate' => 91]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('data-count="42"', $html);
        $this->assertStringContainsString('data-count="91"', $html);
        $this->assertStringContainsString(t('st1', 'en'), $html);

        // and only the two that were supplied
        $this->assertSame(2, substr_count($html, 'gs-count'));
        $this->assertStringNotContainsString(t('st2', 'en'), $html);
    }

    public function test_the_about_badge_returns_with_a_real_founding_year(): void
    {
        config(['stats.years_in_software' => 7]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('>7+<', $html);
        $this->assertStringContainsString(t('yearsIn', 'en'), $html);
    }

    public function test_no_second_copy_of_the_numbers_survives_in_settings(): void
    {
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
}
