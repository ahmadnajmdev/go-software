<?php

namespace Tests\Feature;

use App\Http\Requests\StoreContactRequest;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class QualifyingFormTest extends TestCase
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
        return [['en', '/'], ['ar', '/ar'], ['ckb', '/ckb']];
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'locale' => 'en',
            'service' => 'pos',
            'message' => 'Two branches, four terminals, currently on paper.',
            'name' => 'Aram Hussein',
            'phone' => '+9647510000000',
            'email' => 'aram@example.com',
        ], $overrides);
    }

    #[DataProvider('locales')]
    public function test_step_one_offers_all_six_choices_as_one_tap(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        foreach (StoreContactRequest::SERVICES as $service) {
            $this->assertStringContainsString('name="service" value="'.$service.'"', $html);
        }

        // POS and e-commerce were missing from the old dropdown entirely
        $this->assertStringContainsString(e(t('svcPos', $locale)), $html);
        $this->assertStringContainsString(e(t('svcEcom', $locale)), $html);
        $this->assertSame(6, substr_count($html, 'class="gs-tile"'));
    }

    #[DataProvider('locales')]
    public function test_each_choice_has_its_own_step_two_prompt(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        // the prompts are handed to the component, which swaps them on choice
        foreach (['phWebsite', 'phMobileApp', 'phMgmt', 'phPos', 'phEcom', 'phOther'] as $key) {
            $this->assertStringContainsString(
                json_encode(t($key, $locale), JSON_UNESCAPED_UNICODE),
                $html,
                "no step-two prompt for {$key} in {$locale}"
            );
        }
    }

    #[DataProvider('locales')]
    public function test_step_three_asks_for_a_whatsapp_number_prefilled_with_the_country_code(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $this->assertStringContainsString('value="+964"', $html);
        $this->assertStringContainsString('inputmode="tel"', $html);
        $this->assertStringContainsString(e(t('phWhatsapp', $locale)), $html);
        $this->assertStringContainsString(e(t('phCompany', $locale)), $html);
    }

    #[DataProvider('locales')]
    public function test_step_four_is_optional_and_offers_a_way_out_of_both_questions(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $this->assertStringContainsString(e(t('f4Optional', $locale)), $html);

        // without these two the budget question causes abandonment rather
        // than producing answers
        $this->assertStringContainsString('value="unsure"', $html);
        $this->assertStringContainsString('value="exploring"', $html);
        $this->assertStringContainsString(e(t('bud5', $locale)), $html);
        $this->assertStringContainsString(e(t('tim4', $locale)), $html);
    }

    public function test_a_full_submission_stores_every_qualifying_answer(): void
    {
        $this->post('/contact', $this->payload([
            'company' => 'Folivya',
            'budget' => '8k-20k',
            'timeline' => '1-3-months',
        ]))->assertRedirect()->assertSessionHasNoErrors();

        $lead = ContactSubmission::sole();

        $this->assertSame('pos', $lead->service);
        $this->assertSame('Folivya', $lead->company);
        $this->assertSame('8k-20k', $lead->budget);
        $this->assertSame('1-3-months', $lead->timeline);
        $this->assertSame('+9647510000000', $lead->phone);
    }

    public function test_budget_and_timeline_stay_optional(): void
    {
        $this->post('/contact', $this->payload())->assertRedirect()->assertSessionHasNoErrors();

        $lead = ContactSubmission::sole();
        $this->assertNull($lead->budget);
        $this->assertNull($lead->timeline);
    }

    public function test_the_whatsapp_number_is_now_required(): void
    {
        $payload = $this->payload();
        unset($payload['phone']);

        $this->post('/contact', $payload)->assertInvalid(['phone' => t('errPhone', 'en')]);

        // and it has to contain an actual number
        $this->post('/contact', $this->payload(['phone' => 'call me']))->assertInvalid('phone');
        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_a_service_must_be_chosen_and_must_be_one_of_the_six(): void
    {
        $payload = $this->payload();
        unset($payload['service']);
        $this->post('/contact', $payload)->assertInvalid(['service' => t('errService', 'en')]);

        $this->post('/contact', $this->payload(['service' => 'spaceship']))->assertInvalid('service');
    }

    public function test_an_invented_budget_or_timeline_is_rejected(): void
    {
        $this->post('/contact', $this->payload(['budget' => '1000000']))->assertInvalid('budget');
        $this->post('/contact', $this->payload(['timeline' => 'yesterday']))->assertInvalid('timeline');
    }

    #[DataProvider('locales')]
    public function test_the_form_works_as_one_page_without_javascript(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        // x-show does nothing without Alpine, so all four steps stay visible
        $this->assertSame(4, substr_count($html, '<fieldset x-show="step ==='));

        // the step controls are cloaked, so the submit button is the only one
        $this->assertStringContainsString('class="gs-form-nav" x-cloak', $html);
        $this->assertStringContainsString('type="submit"', $html);
    }

    #[DataProvider('locales')]
    public function test_the_reassurance_line_sits_under_the_button(string $locale, string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $this->assertStringContainsString(e(t('fReassure', $locale)), $html);
        $this->assertStringContainsString(e(t('waPrefer', $locale)), $html);
        $this->assertStringContainsString('wa.me/', $html);
    }

    #[DataProvider('locales')]
    public function test_the_success_state_says_what_the_reply_will_contain(string $locale, string $url): void
    {
        $html = $this->followingRedirects()
            ->from($url)
            ->post('/contact', $this->payload(['locale' => $locale]))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString(e(t('thanksB', $locale)), $html);
        $this->assertStringContainsString('Ahmad', t('thanksB', 'en'));
    }

    public function test_a_rejected_field_comes_back_on_its_own_step(): void
    {
        $html = $this->followingRedirects()
            ->from('/')
            ->post('/contact', $this->payload(['email' => 'nope', 'message' => '']))
            ->assertOk()
            ->getContent();

        // the component reads the errors and jumps to the earliest failing step
        $this->assertStringContainsString(e(t('errMessage', 'en')), $html);
        $this->assertStringContainsString(e(t('errEmailValid', 'en')), $html);

        // and what was typed survives
        $this->assertStringContainsString('value="nope"', $html);
        $this->assertStringContainsString('name="service" value="pos"', $html);
    }

    public function test_the_admin_inbox_shows_the_qualifying_answers(): void
    {
        $this->post('/contact', $this->payload([
            'company' => 'Folivya', 'budget' => 'unsure', 'timeline' => 'exploring',
        ]))->assertRedirect();

        $lead = ContactSubmission::sole();

        $this->actingAs(\App\Models\User::first())
            ->get("/admin/inbox/{$lead->id}")
            ->assertOk()
            ->assertSee('pos')
            ->assertSee('Folivya');
    }
}
