<?php

namespace Tests\Feature;

use App\Models\AnalyticsEvent;
use App\Models\ContactSubmission;
use App\Models\User;
use App\Support\Analytics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function collect(array $payload)
    {
        return $this->postJson('/analytics/collect', $payload);
    }

    // ------------------------------------------------------------- collector

    public function test_an_event_is_recorded(): void
    {
        $this->collect(['name' => 'cta_click', 'page' => '/ckb', 'language' => 'ckb',
            'label' => 'Get a Free Project Estimate', 'location' => 'hero'])->assertNoContent();

        $event = AnalyticsEvent::sole();
        $this->assertSame('cta_click', $event->name);
        $this->assertSame('/ckb', $event->page);
        $this->assertSame('ckb', $event->locale);
        $this->assertSame('hero', $event->location);
    }

    public function test_only_known_event_names_are_recorded(): void
    {
        // The endpoint is public, so the vocabulary is fixed server-side.
        // Nothing is returned but 204: a beacon has nobody waiting to read an
        // error, and a batch must not be lost because one entry was junk.
        $this->collect(['name' => 'something_invented'])->assertNoContent();
        $this->collect(['name' => 'page_view'])->assertNoContent();

        $this->assertSame(1, AnalyticsEvent::count());
        $this->assertSame('page_view', AnalyticsEvent::sole()->name);
    }

    public function test_no_personal_data_is_stored(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.9'])
            ->withHeaders(['User-Agent' => 'Mozilla/5.0 (Linux; Android 12)'])
            ->collect(['name' => 'page_view', 'page' => '/']);

        $row = AnalyticsEvent::sole()->toArray();
        $serialised = json_encode($row);

        $this->assertStringNotContainsString('203.0.113.9', $serialised);
        $this->assertStringNotContainsString('Android', $serialised);
        $this->assertArrayNotHasKey('ip', $row);
        $this->assertArrayNotHasKey('user_agent', $row);

        // the visitor code is a hash, not an identifier we can reverse
        $this->assertSame(32, strlen(AnalyticsEvent::sole()->visitor));
    }

    public function test_the_visitor_code_changes_between_days(): void
    {
        $server = ['REMOTE_ADDR' => '203.0.113.9'];
        $agent = ['User-Agent' => 'Same Browser'];

        $this->travelTo(now()->setDate(2026, 8, 18));
        $this->withServerVariables($server)->withHeaders($agent)->collect(['name' => 'page_view']);

        $this->travelTo(now()->addDay());
        $this->withServerVariables($server)->withHeaders($agent)->collect(['name' => 'page_view']);

        $codes = AnalyticsEvent::pluck('visitor');
        $this->assertCount(2, $codes->unique(), 'the same person is traceable across days');
    }

    public function test_do_not_track_is_honoured_server_side_too(): void
    {
        $this->withHeaders(['DNT' => '1'])->collect(['name' => 'page_view'])->assertNoContent();
        $this->withHeaders(['Sec-GPC' => '1'])->collect(['name' => 'page_view'])->assertNoContent();

        $this->assertSame(0, AnalyticsEvent::count());
    }

    public function test_admins_are_not_counted_in_their_own_numbers(): void
    {
        $this->actingAs(User::first())->collect(['name' => 'page_view'])->assertNoContent();

        $this->assertSame(0, AnalyticsEvent::count());
    }

    public function test_the_collector_is_rate_limited(): void
    {
        for ($i = 0; $i < 60; $i++) {
            $this->collect(['name' => 'page_view'])->assertNoContent();
        }

        $this->collect(['name' => 'page_view'])->assertStatus(429);
    }

    // ------------------------------------------------------------- reporting

    private function seedTraffic(): void
    {
        foreach (range(1, 10) as $i) {
            AnalyticsEvent::create(['name' => 'page_view', 'page' => '/', 'locale' => 'en',
                'visitor' => substr(md5((string) $i), 0, 32), 'created_at' => now()->subDays(2)]);
        }

        AnalyticsEvent::create(['name' => 'form_start', 'visitor' => substr(md5('1'), 0, 32), 'created_at' => now()->subDays(2)]);
        AnalyticsEvent::create(['name' => 'form_step_complete', 'params' => ['step' => 1], 'visitor' => substr(md5('1'), 0, 32), 'created_at' => now()->subDays(2)]);
        AnalyticsEvent::create(['name' => 'form_submit', 'visitor' => substr(md5('1'), 0, 32), 'created_at' => now()->subDays(2)]);
        AnalyticsEvent::create(['name' => 'cta_click', 'location' => 'hero', 'visitor' => substr(md5('2'), 0, 32), 'created_at' => now()->subDays(2)]);

        ContactSubmission::create(['name' => 'A', 'email' => 'a@b.co', 'phone' => '+9647510000000',
            'message' => 'hi', 'service' => 'pos', 'budget' => 'unsure', 'timeline' => 'exploring',
            'locale' => 'en', 'source' => '/', 'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2)]);
    }

    public function test_the_report_counts_what_was_recorded(): void
    {
        $this->seedTraffic();
        $report = Analytics::forDays(30);

        $this->assertSame(10, $report->pageViews());
        $this->assertSame(10, $report->visitors());
        $this->assertSame(1, $report->enquiries());
        $this->assertSame(10.0, $report->conversionRate());
    }

    public function test_conversion_is_null_rather_than_zero_without_traffic(): void
    {
        // an empty dashboard must not imply a real result of nought
        $this->assertNull(Analytics::forDays(30)->conversionRate());
        $this->assertFalse(Analytics::forDays(30)->hasData());
    }

    public function test_the_daily_series_covers_every_day_in_the_window(): void
    {
        $this->seedTraffic();
        $series = Analytics::forDays(7)->daily();

        $this->assertCount(7, $series);
        $this->assertSame(10, $series->sum('views'));
        $this->assertSame(1, $series->sum('enquiries'));
    }

    public function test_the_funnel_reports_each_stage(): void
    {
        $this->seedTraffic();
        $funnel = Analytics::forDays(30)->formFunnel();

        $this->assertSame('Started the form', $funnel->first()['label']);
        $this->assertSame(1, $funnel->first()['count']);
        $this->assertSame(1, $funnel->firstWhere('label', 'Finished step 1')['count']);
        $this->assertSame(0, $funnel->firstWhere('label', 'Finished step 3')['count']);
        $this->assertSame(1, $funnel->last()['count']);
    }

    public function test_breakdowns_group_the_right_things(): void
    {
        $this->seedTraffic();
        $report = Analytics::forDays(30);

        $this->assertSame(1, $report->ctaClicks()['hero']);
        $this->assertSame(10, $report->topPages()['/']);
        $this->assertSame(10, $report->byLanguage()['en']);
        $this->assertSame(1, $report->leadsBy('service')['pos']);
        $this->assertSame(1, $report->leadsBy('budget')['unsure']);
    }

    public function test_the_previous_window_does_not_overlap_this_one(): void
    {
        $report = Analytics::forDays(7);
        $previous = $report->previous();

        AnalyticsEvent::create(['name' => 'page_view', 'visitor' => substr(md5('9'), 0, 32), 'created_at' => now()->subDays(2)]);
        AnalyticsEvent::create(['name' => 'page_view', 'visitor' => substr(md5('8'), 0, 32), 'created_at' => now()->subDays(10)]);

        $this->assertSame(1, $report->pageViews());
        $this->assertSame(1, $previous->pageViews());
    }

    // ------------------------------------------------------------- dashboard

    public function test_the_dashboard_renders_the_analytics(): void
    {
        $this->seedTraffic();

        $this->actingAs(User::first())->get('/admin')->assertOk()
            ->assertSee('Page views')
            ->assertSee('Where people stop in the form')
            ->assertSee('Which CTA gets clicked')
            ->assertSee('Budget people chose');
    }

    public function test_an_empty_dashboard_says_so_instead_of_showing_zeros(): void
    {
        $this->actingAs(User::first())->get('/admin')->assertOk()
            ->assertSee('Nothing recorded yet');
    }

    public function test_the_range_can_be_changed_and_junk_falls_back(): void
    {
        $admin = User::first();

        $this->actingAs($admin)->get('/admin?days=7')->assertOk()->assertSee('Last 7 days');
        $this->actingAs($admin)->get('/admin?days=999')->assertOk();
        $this->actingAs($admin)->get('/admin?days=drop')->assertOk();
    }

    public function test_the_dashboard_stays_behind_the_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    // ------------------------------------------------------------- retention

    public function test_old_events_are_pruned_as_the_privacy_policy_promises(): void
    {
        AnalyticsEvent::create(['name' => 'page_view', 'created_at' => now()->subMonths(13)]);
        AnalyticsEvent::create(['name' => 'page_view', 'created_at' => now()->subMonths(2)]);

        $this->artisan('analytics:prune')->assertSuccessful();

        $this->assertSame(1, AnalyticsEvent::count());
    }

    public function test_the_privacy_policy_discloses_the_event_log(): void
    {
        foreach (['/privacy-policy', '/ar/privacy-policy', '/ckb/privacy-policy'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertMatchesRegularExpression('/(our own record|سجلّ خاص بنا|تۆمارێکی تایبەت بە خۆمان)/u', $html);
        }

        // and it states the retention period the command enforces
        $this->assertStringContainsString('twelve months', $this->get('/privacy-policy')->getContent());
    }

    public function test_a_batch_is_stored_in_one_request(): void
    {
        // One request per event is enough to make a database-backed cache lock
        // on the rate limiter's counter, which loses the request entirely.
        $this->postJson('/analytics/collect', ['events' => [
            ['name' => 'form_start'],
            ['name' => 'form_step_complete', 'step' => 1],
            ['name' => 'form_step_complete', 'step' => 2],
            ['name' => 'form_submit', 'service' => 'pos'],
        ]])->assertNoContent();

        $this->assertSame(4, AnalyticsEvent::count());
        $this->assertSame(2, AnalyticsEvent::where('name', 'form_step_complete')->count());
        $this->assertSame(['step' => 1], AnalyticsEvent::where('name', 'form_step_complete')->first()->params);
        $this->assertSame(['service' => 'pos'], AnalyticsEvent::where('name', 'form_submit')->sole()->params);
    }

    public function test_one_bad_event_does_not_lose_the_rest_of_the_batch(): void
    {
        $this->postJson('/analytics/collect', ['events' => [
            ['name' => 'page_view'],
            ['name' => 'not_a_real_event'],
            ['name' => 'cta_click', 'location' => 'hero'],
        ]])->assertNoContent();

        $this->assertSame(2, AnalyticsEvent::count());
        $this->assertSame(0, AnalyticsEvent::where('name', 'not_a_real_event')->count());
    }

    public function test_a_batch_is_capped(): void
    {
        $events = array_fill(0, 40, ['name' => 'page_view']);

        $this->postJson('/analytics/collect', ['events' => $events])->assertNoContent();

        $this->assertSame(20, AnalyticsEvent::count());
    }

    public function test_every_event_in_a_batch_shares_one_visitor_code(): void
    {
        $this->postJson('/analytics/collect', ['events' => [
            ['name' => 'page_view'], ['name' => 'cta_click'], ['name' => 'form_start'],
        ]])->assertNoContent();

        $this->assertCount(1, AnalyticsEvent::pluck('visitor')->unique());
    }

    public function test_do_not_track_stops_a_whole_batch(): void
    {
        $this->withHeaders(['DNT' => '1'])->postJson('/analytics/collect', ['events' => [
            ['name' => 'page_view'], ['name' => 'form_submit'],
        ]])->assertNoContent();

        $this->assertSame(0, AnalyticsEvent::count());
    }

    public function test_a_malformed_payload_is_shrugged_off(): void
    {
        foreach ([['events' => 'nope'], ['events' => [null, 'x', 7]], []] as $payload) {
            $this->postJson('/analytics/collect', $payload)->assertNoContent();
        }

        $this->assertSame(0, AnalyticsEvent::count());
    }
}
