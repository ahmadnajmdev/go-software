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

            foreach (['', '/projects'] as $path) {
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
}
