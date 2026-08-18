<?php

namespace App\Support;

use App\Models\AnalyticsEvent;
use App\Models\ContactSubmission;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Reporting over the first-party event log and the leads table.
 *
 * Every figure here is measured, not estimated — it comes from events this
 * site recorded or from rows in contact_submissions. Where a number cannot be
 * computed it is returned as null rather than zero, so the dashboard can say
 * "no data yet" instead of implying a real result of nought.
 */
class Analytics
{
    public function __construct(
        private CarbonImmutable $from,
        private CarbonImmutable $to,
    ) {
    }

    public static function forDays(int $days): self
    {
        $to = CarbonImmutable::now()->endOfDay();

        return new self($to->subDays($days - 1)->startOfDay(), $to);
    }

    /** The same window, immediately before this one, for comparison. */
    public function previous(): self
    {
        $length = $this->from->diffInSeconds($this->to);

        return new self($this->from->subSeconds($length + 1), $this->from->subSecond());
    }

    public function days(): int
    {
        return max(1, (int) ceil($this->from->diffInDays($this->to)) + 1);
    }

    // ---------------------------------------------------------------- totals

    public function pageViews(): int
    {
        return $this->events()->named('page_view')->count();
    }

    public function visitors(): int
    {
        return $this->events()->distinct()->count('visitor');
    }

    public function enquiries(): int
    {
        return ContactSubmission::whereBetween('created_at', [$this->from, $this->to])->count();
    }

    /** Enquiries per hundred visitors. Null until anyone has visited. */
    public function conversionRate(): ?float
    {
        $visitors = $this->visitors();

        return $visitors > 0 ? round($this->enquiries() / $visitors * 100, 1) : null;
    }

    public function hasData(): bool
    {
        return $this->events()->exists() || $this->enquiries() > 0;
    }

    // ----------------------------------------------------------------- daily

    /** @return Collection<int, array{date: string, views: int, enquiries: int}> */
    public function daily(): Collection
    {
        $views = $this->events()->named('page_view')
            ->select(DB::raw('date(created_at) as d'), DB::raw('count(*) as c'))
            ->groupBy('d')->pluck('c', 'd');

        $leads = ContactSubmission::whereBetween('created_at', [$this->from, $this->to])
            ->select(DB::raw('date(created_at) as d'), DB::raw('count(*) as c'))
            ->groupBy('d')->pluck('c', 'd');

        $series = collect();

        for ($day = $this->from; $day->lessThanOrEqualTo($this->to); $day = $day->addDay()) {
            $key = $day->toDateString();
            $series->push([
                'date' => $key,
                'views' => (int) ($views[$key] ?? 0),
                'enquiries' => (int) ($leads[$key] ?? 0),
            ]);
        }

        return $series;
    }

    // ------------------------------------------------------------ the funnel

    /**
     * Where people stop. The stepped form exists to reduce abandonment, so
     * this is the report that says whether it worked.
     *
     * @return Collection<int, array{label: string, count: int, share: ?float}>
     */
    public function formFunnel(): Collection
    {
        $started = $this->events()->named('form_start')->distinct()->count('visitor');

        $steps = $this->events()->named('form_step_complete')
            ->select(DB::raw("json_extract(params, '$.step') as step"), DB::raw('count(distinct visitor) as c'))
            ->groupBy('step')->pluck('c', 'step');

        $submitted = $this->events()->named('form_submit')->distinct()->count('visitor');

        $rows = collect([['label' => 'Started the form', 'count' => $started]]);

        for ($step = 1; $step <= 4; $step++) {
            $rows->push([
                'label' => "Finished step {$step}",
                'count' => (int) ($steps[$step] ?? $steps[(string) $step] ?? 0),
            ]);
        }

        $rows->push(['label' => 'Submitted', 'count' => $submitted]);

        return $rows->map(fn ($row) => $row + [
            'share' => $started > 0 ? round($row['count'] / $started * 100) : null,
        ]);
    }

    /** Which field people are rejected on most. */
    public function formErrors(): Collection
    {
        return $this->paramCounts('form_error', 'field');
    }

    // ------------------------------------------------------------ breakdowns

    public function topPages(int $limit = 8): Collection
    {
        return $this->events()->named('page_view')
            ->select('page', DB::raw('count(*) as total'))
            ->groupBy('page')->orderByDesc('total')->limit($limit)
            ->pluck('total', 'page');
    }

    /** Which call to action actually gets clicked, and from where. */
    public function ctaClicks(): Collection
    {
        return $this->events()->named('cta_click')
            ->select('location', DB::raw('count(*) as total'))
            ->whereNotNull('location')
            ->groupBy('location')->orderByDesc('total')
            ->pluck('total', 'location');
    }

    public function whatsappClicks(): Collection
    {
        return $this->paramCounts('whatsapp_click', 'source');
    }

    public function projectViews(): Collection
    {
        return $this->paramCounts('project_view', 'project');
    }

    public function faqOpens(): Collection
    {
        return $this->paramCounts('faq_open', 'question');
    }

    public function contactClicks(): array
    {
        return [
            'WhatsApp' => $this->events()->named('whatsapp_click')->count(),
            'Phone' => $this->events()->named('phone_click')->count(),
            'Email' => $this->events()->named('email_click')->count(),
        ];
    }

    public function byLanguage(): Collection
    {
        return $this->events()->named('page_view')
            ->select('locale', DB::raw('count(*) as total'))
            ->whereNotNull('locale')
            ->groupBy('locale')->orderByDesc('total')
            ->pluck('total', 'locale');
    }

    // --------------------------------------------------------------- enquiry

    /** @return Collection<string, int> */
    public function leadsBy(string $column): Collection
    {
        return ContactSubmission::whereBetween('created_at', [$this->from, $this->to])
            ->whereNotNull($column)->where($column, '!=', '')
            ->select($column, DB::raw('count(*) as total'))
            ->groupBy($column)->orderByDesc('total')
            ->pluck('total', $column);
    }

    public function medianReplyGap(): ?string
    {
        return null; // reserved: needs a "replied_at" we do not record yet
    }

    // ----------------------------------------------------------------- inner

    private function events()
    {
        return AnalyticsEvent::query()->between($this->from, $this->to);
    }

    /** Count one JSON parameter across an event, most common first. */
    private function paramCounts(string $event, string $key, int $limit = 8): Collection
    {
        return $this->events()->named($event)
            ->select(DB::raw("json_extract(params, '$.{$key}') as k"), DB::raw('count(*) as total'))
            ->groupBy('k')->orderByDesc('total')->limit($limit)
            ->pluck('total', 'k')
            ->mapWithKeys(fn ($total, $k) => [trim((string) $k, '"') ?: '(not set)' => $total])
            ->filter(fn ($_, $k) => $k !== '(not set)');
    }
}
