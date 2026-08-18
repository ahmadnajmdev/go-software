<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function realTestimonial(array $overrides = []): Testimonial
    {
        return Testimonial::create(array_merge([
            'position' => 1,
            'author' => 'Rebin Aziz',
            'role' => ['en' => 'Operations Manager'],
            'company' => 'Folivya',
            'quote' => ['en' => 'They understood our shops before writing a line of code.'],
            'result' => ['en' => 'Monthly stock-taking went from three days to two hours.'],
            'rating' => 5,
        ], $overrides));
    }

    public function test_no_invented_testimonial_ships(): void
    {
        // the seeded "Tom Harding", "Priya Nair" and "Sarah Doyle" quotes were
        // written by nobody
        $this->assertSame(0, Testimonial::count());

        $html = $this->get('/')->assertOk()->getContent();

        foreach (['Tom Harding', 'Priya Nair', 'Sarah Doyle'] as $invented) {
            $this->assertStringNotContainsString($invented, $html);
        }
    }

    public function test_the_quotes_section_does_not_render_until_one_is_real(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString(t('tstVoicesTitle', 'en'), $html);
        $this->assertStringNotContainsString(t('tstResult', 'en'), $html);

        // the founder's own quote is real and stays; no client quote does
        $section = $this->clientsSection($html);
        $this->assertStringNotContainsString('<blockquote', $section);
    }

    public function test_the_logo_strip_is_honestly_labelled_and_rendered_once(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        // it used to be headed "Testimonials" and contain only logos
        $this->assertStringContainsString(t('tstTitle', 'en'), $html);
        $this->assertSame('Companies we work with', t('tstTitle', 'en'));

        // each logo appears once here, not twice for a marquee loop
        $client = Client::ordered()->first();
        $section = substr($html, strpos($html, t('tstTitle', 'en')));
        $section = substr($section, 0, strpos($section, '</section>'));
        $this->assertSame(1, substr_count($section, $client->name));
    }

    public function test_a_real_testimonial_renders_with_its_result(): void
    {
        $testimonial = $this->realTestimonial();

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString($testimonial->author, $html);
        $this->assertStringContainsString('Folivya', $html);
        $this->assertStringContainsString(e($testimonial->tr('quote')), $html);
        $this->assertStringContainsString(e($testimonial->tr('result')), $html);
        $this->assertStringContainsString(t('tstVoicesTitle', 'en'), $html);
    }

    public function test_a_testimonial_without_a_quote_is_not_shown(): void
    {
        $this->realTestimonial(['quote' => ['en' => '']]);

        $this->get('/')->assertOk()->assertDontSee('Rebin Aziz');
    }

    public function test_an_optional_video_embeds_when_supplied(): void
    {
        $this->realTestimonial(['video_url' => 'https://www.youtube.com/embed/abc123']);

        $this->get('/')->assertOk()->assertSee('youtube.com/embed/abc123', false);
    }

    public function test_the_admin_accepts_a_full_testimonial(): void
    {
        $this->actingAs(User::first())->post('/admin/testimonials', [
            'author' => 'Rebin Aziz',
            'company' => 'Folivya',
            'rating' => 5,
            'role' => ['en' => 'Operations Manager'],
            'quote' => ['en' => 'They understood our shops first.'],
            'result' => ['en' => 'Stock-taking: three days to two hours.'],
            'video_url' => 'https://www.youtube.com/embed/abc123',
        ])->assertRedirect('/admin/testimonials')->assertSessionHasNoErrors();

        $saved = Testimonial::sole();
        $this->assertSame('Folivya', $saved->company);
        $this->assertSame('Stock-taking: three days to two hours.', $saved->tr('result'));
    }

    public function test_a_watch_link_is_rejected_so_the_embed_cannot_silently_fail(): void
    {
        foreach ([
            'https://www.youtube.com/watch?v=abc123',
            'https://evil.example/embed/x',
            'http://www.youtube.com/embed/abc',
        ] as $bad) {
            $this->actingAs(User::first())->post('/admin/testimonials', [
                'author' => 'X', 'rating' => 5,
                'role' => ['en' => 'r'], 'quote' => ['en' => 'q'],
                'video_url' => $bad,
            ])->assertSessionHasErrors('video_url');
        }
    }

    /** The clients/testimonials section only. */
    private function clientsSection(string $html): string
    {
        $start = strpos($html, t('tstTitle', 'en'));
        $this->assertNotFalse($start, 'the clients section is missing');

        return substr($html, $start, strpos($html, '</section>', $start) - $start);
    }
}
