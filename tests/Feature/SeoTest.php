<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SeoTest extends TestCase
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
        $paths = ['', '/projects', '/privacy-policy', '/terms-of-service',
            '/services/website-development', '/services/pos-inventory', '/services/ecommerce'];
        $cases = [];

        foreach (['en' => '', 'ar' => '/ar', 'ckb' => '/ckb'] as $locale => $prefix) {
            foreach ($paths as $path) {
                $cases["{$locale}{$path}"] = [$locale, ($prefix.$path) ?: '/'];
            }
        }

        return $cases;
    }

    private function headOf(string $url): string
    {
        $html = $this->get($url)->assertOk()->getContent();

        return substr($html, 0, strpos($html, '</head>'));
    }

    #[DataProvider('pages')]
    public function test_hreflang_alternates_point_at_this_page_not_the_home_page(string $locale, string $url): void
    {
        $head = $this->headOf($url);
        $base = ltrim(preg_replace('#^/(ar|ckb)#', '', $url), '/');

        foreach (['en' => '', 'ar' => '/ar', 'ckb' => '/ckb'] as $alt => $prefix) {
            $expected = url($prefix.($base ? '/'.$base : ''));
            $this->assertStringContainsString(
                '<link rel="alternate" hreflang="'.$alt.'" href="'.$expected.'">',
                $head,
                "{$url} has the wrong {$alt} alternate"
            );
        }

        $this->assertStringContainsString('hreflang="x-default" href="'.url('/'.$base).'"', $head);
    }

    #[DataProvider('pages')]
    public function test_the_canonical_is_this_page_in_this_language(string $locale, string $url): void
    {
        $head = $this->headOf($url);
        $base = ltrim(preg_replace('#^/(ar|ckb)#', '', $url), '/');
        $prefix = $locale === 'en' ? '' : '/'.$locale;

        $this->assertStringContainsString(
            '<link rel="canonical" href="'.url($prefix.($base ? '/'.$base : '')).'">',
            $head
        );
    }

    public function test_every_page_has_a_unique_title_and_description(): void
    {
        $titles = [];
        $descriptions = [];

        foreach (self::pages() as [$locale, $url]) {
            $head = $this->headOf($url);
            preg_match('#<title>(.*?)</title>#s', $head, $t);
            preg_match('#<meta name="description" content="(.*?)">#s', $head, $d);

            $this->assertNotEmpty($t[1], "{$url} has no title");
            $this->assertNotEmpty($d[1], "{$url} has no description");

            $titles[$url] = $t[1];
            $descriptions[$url] = $d[1];
        }

        // / and /projects used to share a description verbatim
        $this->assertSame(count($titles), count(array_unique($titles)), 'two pages share a <title>');
        $this->assertSame(count($descriptions), count(array_unique($descriptions)), 'two pages share a description');
    }

    public function test_the_home_title_leads_with_what_we_sell_and_where(): void
    {
        // it used to be "GoSoftware — SOFTWARE DEVELOPMENT", spending the most
        // valuable 60 characters on the site saying nothing
        $this->assertSame('Custom Software, Apps & POS Systems in Erbil | GoSoftware', t('metaHomeTitle', 'en'));
        $this->assertStringContainsString('Erbil', $this->headOf('/'));
    }

    public function test_titles_are_escaped_exactly_once(): void
    {
        $head = $this->headOf('/services/ecommerce');

        $this->assertStringContainsString('Payment &amp; Delivery', $head);
        $this->assertStringNotContainsString('&amp;amp;', $head);
    }

    #[DataProvider('pages')]
    public function test_open_graph_and_twitter_cards_are_complete(string $locale, string $url): void
    {
        $head = $this->headOf($url);

        foreach (['og:type', 'og:site_name', 'og:locale', 'og:title', 'og:description', 'og:url',
            'og:image', 'og:image:width', 'og:image:height'] as $property) {
            $this->assertMatchesRegularExpression(
                '#<meta property="'.preg_quote($property, '#').'" content="[^"]+">#',
                $head, "{$url} is missing {$property}"
            );
        }

        $this->assertStringContainsString('name="twitter:card" content="summary_large_image"', $head);
        $this->assertStringContainsString('og-default.png', $head);
    }

    public function test_the_branded_og_image_exists_and_is_the_right_size(): void
    {
        $path = public_path('images/og-default.png');

        $this->assertFileExists($path);
        [$width, $height] = getimagesize($path);
        $this->assertSame(1200, $width);
        $this->assertSame(630, $height);
    }

    public function test_local_business_schema_describes_the_erbil_office(): void
    {
        $schema = $this->schemaOfType($this->get('/')->getContent(), 'ProfessionalService');

        $this->assertNotNull($schema);
        $this->assertSame('Erbil', $schema['address']['addressLocality']);
        $this->assertSame('IQ', $schema['address']['addressCountry']);
        $this->assertStringContainsString('Justice Tower', $schema['address']['streetAddress']);
        $this->assertSame(36.1821139, $schema['geo']['latitude']);
        $this->assertSame(43.9785422, $schema['geo']['longitude']);
        $this->assertSame(gs_setting('contact.phone'), $schema['telephone']);
        $this->assertEqualsCanonicalizing(['ckb', 'ar', 'en'], $schema['availableLanguage']);
        $this->assertNotEmpty($schema['openingHoursSpecification']);
    }

    public function test_organization_schema_names_the_founder(): void
    {
        $schema = $this->schemaOfType($this->get('/')->getContent(), 'Organization');

        $this->assertNotNull($schema);
        $this->assertSame(gs_setting('about.ceo_name'), $schema['founder']['name']);
    }

    public function test_social_profiles_appear_as_sameas_once_configured(): void
    {
        $this->assertArrayNotHasKey('sameAs', $this->schemaOfType($this->get('/')->getContent(), 'Organization'));

        \App\Support\Settings::set('social.linkedin', 'https://linkedin.com/company/gosoftware');

        $schema = $this->schemaOfType($this->get('/')->getContent(), 'Organization');
        $this->assertContains('https://linkedin.com/company/gosoftware', $schema['sameAs']);
    }

    public function test_service_pages_carry_breadcrumbs(): void
    {
        $schema = $this->schemaOfType($this->get('/ckb/services/pos-inventory')->getContent(), 'BreadcrumbList');

        $this->assertNotNull($schema);
        $this->assertCount(3, $schema['itemListElement']);
        $this->assertSame(t('navHome', 'ckb'), $schema['itemListElement'][0]['name']);
        $this->assertStringContainsString('/ckb/services/pos-inventory', $schema['itemListElement'][2]['item']);
    }

    public function test_the_sitemap_lists_every_page_in_every_language(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk();
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml, 'the sitemap is not well-formed XML');

        $locations = [];
        foreach ($xml->url as $url) {
            $locations[] = (string) $url->loc;
        }

        // 4 static + 7 services, times 3 languages
        $this->assertCount(33, $locations);
        $this->assertSame($locations, array_unique($locations));

        foreach ([url('/'), url('/ckb'), url('/ar/projects'), url('/ckb/services/pos-inventory'),
            url('/ar/privacy-policy')] as $expected) {
            $this->assertContains($expected, $locations);
        }
    }

    public function test_every_url_in_the_sitemap_actually_resolves(): void
    {
        $xml = simplexml_load_string($this->get('/sitemap.xml')->getContent());

        foreach ($xml->url as $url) {
            $path = parse_url((string) $url->loc, PHP_URL_PATH) ?: '/';
            $this->get($path)->assertOk();
        }
    }

    public function test_robots_allows_the_site_and_points_at_the_sitemap(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Sitemap:', $robots);
        $this->assertStringContainsString('sitemap.xml', $robots);
        $this->assertStringContainsString('Disallow: /admin', $robots);
        $this->assertStringNotContainsString("\nDisallow: /\n", $robots);
    }

    private function schemaOfType(string $html, string $type): ?array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $blocks);

        foreach ($blocks[1] as $json) {
            $decoded = json_decode($json, true);
            if (($decoded['@type'] ?? null) === $type) {
                return $decoded;
            }
        }

        return null;
    }
}
