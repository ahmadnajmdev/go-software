<?php

namespace Tests\Feature;

use App\Models\ContactSubmission;
use App\Models\User;
use App\Notifications\NewContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Notification::fake();
    }

    public static function locales(): array
    {
        return [['en'], ['ar'], ['ckb']];
    }

    private function path(string $locale): string
    {
        return $locale === 'en' ? '/' : '/'.$locale;
    }

    private function payload(string $locale, array $overrides = []): array
    {
        return array_merge([
            'name' => 'Aram Hussein',
            'email' => 'aram@example.com',
            'phone' => '+9647510000000',
            'service' => 'system',
            'message' => 'We run four shops on Excel and need a proper system.',
            'locale' => $locale,
        ], $overrides);
    }

    #[DataProvider('locales')]
    public function test_a_submission_is_stored_and_the_team_is_notified(string $locale): void
    {
        $this->from($this->path($locale))
            ->post('/contact', $this->payload($locale))
            ->assertRedirect()
            ->assertSessionHas('contact_sent', true)
            ->assertSessionHasNoErrors();

        $lead = ContactSubmission::sole();

        $this->assertSame('Aram Hussein', $lead->name);
        $this->assertSame('aram@example.com', $lead->email);
        $this->assertSame('system', $lead->service);
        $this->assertSame($locale, $lead->locale);

        Notification::assertSentTo(User::first(), NewContactSubmission::class);
    }

    #[DataProvider('locales')]
    public function test_the_language_recorded_is_the_one_the_visitor_browsed_in(string $locale): void
    {
        // POST /contact carries no locale prefix; before this was fixed every
        // lead was filed as English whatever page it came from.
        $this->post('/contact', $this->payload($locale))->assertRedirect();

        $this->assertSame($locale, ContactSubmission::sole()->locale);
    }

    public function test_the_referring_page_supplies_the_locale_when_the_field_is_missing(): void
    {
        $payload = $this->payload('ckb');
        unset($payload['locale']);

        $this->from('/ckb/projects')->post('/contact', $payload)->assertRedirect();

        $this->assertSame('ckb', ContactSubmission::sole()->locale);
    }

    public function test_an_unknown_locale_falls_back_rather_than_being_stored(): void
    {
        $this->post('/contact', $this->payload('en', ['locale' => 'de']))->assertRedirect();

        $this->assertSame('en', ContactSubmission::sole()->locale);
    }

    #[DataProvider('locales')]
    public function test_validation_errors_come_back_in_the_visitors_language(string $locale): void
    {
        $response = $this->from($this->path($locale))
            ->post('/contact', ['locale' => $locale, 'name' => '']);

        $response->assertRedirect($this->path($locale) === '/'
            ? url('/').'#contact'
            : url($this->path($locale)).'#contact');

        $response->assertInvalid([
            'name' => t('errName', $locale),
            'email' => t('errEmail', $locale),
            'message' => t('errMessage', $locale),
        ]);

        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_errors_render_on_the_page_with_the_input_preserved(): void
    {
        $html = $this->followingRedirects()
            ->from('/ckb')
            ->post('/contact', ['locale' => 'ckb', 'name' => 'Aram', 'email' => 'not-an-email', 'message' => ''])
            ->assertOk()
            ->getContent();

        // the visitor sees why it failed, in Kurdish, instead of a silent reload
        $this->assertStringContainsString(e(t('errEmailValid', 'ckb')), $html);
        $this->assertStringContainsString(e(t('errMessage', 'ckb')), $html);
        $this->assertStringContainsString(e(t('errGeneric', 'ckb')), $html);

        // and what they typed is still in the form
        $this->assertStringContainsString('value="Aram"', $html);
        $this->assertStringContainsString('value="not-an-email"', $html);
    }

    public function test_campaign_and_request_context_are_recorded(): void
    {
        // land on a campaign link, browse, then submit from another page
        $this->get('/ckb?utm_source=instagram&utm_medium=paid&utm_campaign=pos_erbil');
        $this->get('/ckb/projects');

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.9'])
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (Linux; Android 12)'])
            ->from('/ckb/projects')
            ->post('/contact', $this->payload('ckb', ['source' => '/ckb/projects']))
            ->assertRedirect();

        $lead = ContactSubmission::sole();

        $this->assertSame('instagram', $lead->utm_source);
        $this->assertSame('paid', $lead->utm_medium);
        $this->assertSame('pos_erbil', $lead->utm_campaign);
        $this->assertSame('/ckb/projects', $lead->source);
        $this->assertSame('203.0.113.9', $lead->ip);
        $this->assertStringContainsString('Android 12', $lead->user_agent);
    }

    public function test_first_touch_attribution_wins_over_a_later_campaign(): void
    {
        $this->get('/?utm_source=instagram&utm_campaign=first');
        $this->get('/?utm_source=google&utm_campaign=second');

        $this->post('/contact', $this->payload('en'))->assertRedirect();

        $this->assertSame('instagram', ContactSubmission::sole()->utm_source);
        $this->assertSame('first', ContactSubmission::sole()->utm_campaign);
    }

    public function test_the_source_falls_back_to_the_referring_page(): void
    {
        $payload = $this->payload('en');
        unset($payload['source']);

        $this->from('/projects')->post('/contact', $payload)->assertRedirect();

        $this->assertSame('/projects', ContactSubmission::sole()->source);
    }

    public function test_the_honeypot_rejects_a_bot_without_storing_or_notifying(): void
    {
        $this->postJson('/contact', $this->payload('en', ['website' => 'http://spam.example']))
            ->assertStatus(422);

        $this->assertSame(0, ContactSubmission::count());
        Notification::assertNothingSent();
    }

    public function test_submissions_are_rate_limited_per_ip(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/contact', $this->payload('en'))->assertOk();
        }

        $this->postJson('/contact', $this->payload('en'))->assertStatus(429);
        $this->assertSame(10, ContactSubmission::count());
    }

    public function test_a_failing_mailer_never_loses_a_stored_lead(): void
    {
        Notification::shouldReceive('send')->andThrow(new \RuntimeException('SMTP down'));

        $this->postJson('/contact', $this->payload('en'))->assertOk()->assertJson(['ok' => true]);

        $this->assertSame(1, ContactSubmission::count());
    }

    public function test_the_form_carries_the_locale_and_page_it_was_submitted_from(): void
    {
        $html = $this->get('/ckb')->assertOk()->getContent();
        $this->assertStringContainsString('name="locale" value="ckb"', $html);
        $this->assertStringContainsString('name="source" value="/ckb"', $html);

        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('name="locale" value="en"', $html);
        $this->assertStringContainsString('name="source" value="/"', $html);
    }
}
