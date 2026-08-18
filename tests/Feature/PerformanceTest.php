<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PerformanceTest extends TestCase
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
    public function test_the_map_does_not_load_until_someone_asks_for_it(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        // the embed was 434KB of an 817KB page, on every view
        $this->assertStringContainsString('gs-map-facade', $html);
        $this->assertStringContainsString('<template x-if="loaded">', $html);

        // the iframe markup exists but sits inside the template, so the
        // browser makes no request for it until the button is pressed
        $facadeStart = strpos($html, 'gs-map-facade');
        $this->assertGreaterThan(strpos($html, '<template x-if="loaded">'), $facadeStart);
        $this->assertStringContainsString(e(t('mapLoad', $locale)), $html);
    }

    public function test_directions_still_work_without_loading_the_map(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('google.com/maps/dir/', $html);
        $this->assertStringContainsString(e(t('getDirections', 'en')), $html);
    }

    public function test_the_arabic_font_is_only_served_where_it_is_needed(): void
    {
        // ~45KB that an English visitor has no use for
        $this->assertStringNotContainsString('IBM+Plex+Sans+Arabic', $this->get('/')->getContent());

        foreach (['/ar', '/ckb'] as $url) {
            $this->assertStringContainsString('IBM+Plex+Sans+Arabic', $this->get($url)->getContent());
        }
    }

    #[DataProvider('locales')]
    public function test_fonts_swap_rather_than_blocking_the_render(string $locale, string $url): void
    {
        $this->assertStringContainsString('display=swap', $this->get($url)->getContent());
    }

    #[DataProvider('locales')]
    public function test_below_fold_images_are_lazy_and_the_hero_is_not(string $locale, string $url): void
    {
        \App\Support\Settings::set('images.hero', 'uploads/2026/08/hero.jpg');
        \App\Support\Settings::set('images.about_main', 'uploads/2026/08/office.jpg');

        $html = $this->get($url)->assertOk()->getContent();

        preg_match('#<img[^>]+hero\.jpg[^>]*>#', $html, $hero);
        $this->assertNotEmpty($hero, 'the hero image is missing');
        $this->assertStringNotContainsString('loading="lazy"', $hero[0]);
        $this->assertStringContainsString('fetchpriority="high"', $hero[0]);

        preg_match('#<img[^>]+office\.jpg[^>]*>#', $html, $below);
        $this->assertNotEmpty($below);
        $this->assertStringContainsString('loading="lazy"', $below[0]);
        $this->assertStringContainsString('decoding="async"', $below[0]);
    }

    #[DataProvider('locales')]
    public function test_every_image_reserves_its_space(string $locale, string $url): void
    {
        \App\Support\Settings::set('images.hero', 'uploads/2026/08/hero.jpg');

        $html = $this->get($url)->assertOk()->getContent();
        preg_match_all('#<img[^>]*>#', $html, $images);

        foreach ($images[0] as $img) {
            // an <img> without dimensions collapses then jumps: that is CLS
            $this->assertMatchesRegularExpression('#\bwidth="\d+"#', $img, "no width on: {$img}");
            $this->assertMatchesRegularExpression('#\bheight="\d+"#', $img, "no height on: {$img}");
        }
    }

    public function test_we_only_preconnect_to_origins_we_still_use(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        preg_match_all('#<link rel="preconnect" href="([^"]+)"#', $html, $preconnects);

        foreach ($preconnects[1] as $origin) {
            $this->assertContains($origin, [
                'https://fonts.googleapis.com',
                'https://fonts.gstatic.com',
            ], "preconnecting to {$origin}, which is not requested on load");
        }
    }
}
