@extends('admin.layout')

@section('title', 'Dashboard')

@section('header-actions')
    <div class="gs-range">
        @foreach ($ranges as $value => $label)
            <a href="{{ route('admin.dashboard', ['days' => $value]) }}" class="{{ $days === $value ? 'on' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
@endsection

@section('content')
@php
    use Illuminate\Support\Str;

    $series = $analytics->daily();
    $peak = max(1, $series->max('views'));
    $peakLeads = max(1, $series->max('enquiries'));
    $funnel = $analytics->formFunnel();
    $started = $funnel->first()['count'] ?? 0;

    $budgets = ['under-3k' => 'Under $3,000', '3k-8k' => '$3,000 – 8,000', '8k-20k' => '$8,000 – 20,000',
        '20k-plus' => '$20,000+', 'unsure' => 'Not sure yet'];
    $timelines = ['asap' => 'As soon as possible', '1-3-months' => '1–3 months',
        '3-6-months' => '3–6 months', 'exploring' => 'Just exploring'];
    $languages = ['en' => 'English', 'ar' => 'Arabic', 'ckb' => 'Kurdish'];
@endphp

{{-- Headline numbers, each against the previous window of the same length --}}
<div class="gs-kpis">
    @foreach ($headline as $kpi)
        @php
            $value = $kpi['value'];
            $was = $kpi['was'];
            $suffix = $kpi['suffix'] ?? '';
            $delta = ($value !== null && $was !== null && $was > 0)
                ? round(($value - $was) / $was * 100)
                : null;
        @endphp
        <div class="gs-kpi">
            <div class="k">{{ $kpi['label'] }}</div>
            <div class="v">{{ $value === null ? '—' : $value.$suffix }}</div>
            <div class="d {{ $delta === null ? 'flat' : ($delta > 0 ? 'up' : ($delta < 0 ? 'down' : 'flat')) }}">
                @if ($delta === null)
                    no earlier data to compare
                @else
                    {{ $delta > 0 ? '▲' : ($delta < 0 ? '▼' : '■') }} {{ abs($delta) }}% vs previous {{ $days }} days
                @endif
            </div>
        </div>
    @endforeach
</div>

@unless ($analytics->hasData())
    <div class="card">
        <h2>Nothing recorded yet</h2>
        <p class="hint" style="margin-top:6px">
            These figures come from this site's own event log — no third party involved, and they
            appear as soon as people start visiting. Nothing here is estimated: every number is
            either an event this site recorded or a row in the inbox.
        </p>
    </div>
@endunless

<div class="card">
    <h2>Traffic and enquiries</h2>
    <div class="gs-chart">
        @foreach ($series as $day)
            <div class="bar" title="{{ $day['date'] }} — {{ $day['views'] }} views, {{ $day['enquiries'] }} enquiries">
                <div class="fill" style="height: {{ round($day['views'] / $peak * 100) }}%"></div>
                @if ($day['enquiries'])
                    <div class="lead" style="height: {{ max(6, round($day['enquiries'] / $peakLeads * 45)) }}%"></div>
                @endif
            </div>
        @endforeach
    </div>
    <div class="gs-chart-axis">
        <span>{{ $series->first()['date'] ?? '' }}</span>
        <span>{{ $series->last()['date'] ?? '' }}</span>
    </div>
    <div class="gs-legend">
        <span><i style="background:var(--accent,#2ca69c)"></i>Page views</span>
        <span><i style="background:#0d1826"></i>Enquiries</span>
    </div>
</div>

{{-- The report the stepped form exists to justify --}}
<div class="card">
    <h2>Where people stop in the form</h2>
    <p class="hint" style="margin:-6px 0 14px">Unique visitors reaching each stage. The biggest drop is where to look first.</p>
    @if ($started)
        <div class="gs-funnel">
            @foreach ($funnel as $i => $row)
                @php($prev = $i > 0 ? $funnel[$i - 1]['count'] : null)
                @php($lost = $prev !== null && $prev > 0 ? round(($prev - $row['count']) / $prev * 100) : 0)
                <div class="gs-funnel-row {{ $lost >= 40 ? 'drop' : '' }}">
                    <div>{{ $row['label'] }}</div>
                    <div class="track"><div class="fill" style="width: {{ $row['share'] ?? 0 }}%"></div></div>
                    <div class="pct">{{ $row['count'] }}</div>
                </div>
            @endforeach
        </div>
    @else
        <p class="gs-empty">Nobody has started the form in this period.</p>
    @endif
</div>

<div class="gs-panels">
    <div class="card">
        <h2>Which CTA gets clicked</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->ctaClicks(),
            'empty' => 'No call-to-action clicks yet.',
            'format' => fn ($k) => Str::of($k)->replace('_', ' ')->title()->toString(),
        ])
    </div>

    <div class="card">
        <h2>How people reach out</h2>
        @include('admin.partials.bar-list', [
            'rows' => collect($analytics->contactClicks())->filter(),
            'empty' => 'No WhatsApp, phone or email clicks yet.',
        ])
    </div>

    <div class="card">
        <h2>WhatsApp, by where it was clicked</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->whatsappClicks(),
            'empty' => 'No WhatsApp clicks yet.',
            'format' => fn ($k) => Str::of($k)->replace('_', ' ')->title()->toString(),
        ])
    </div>

    <div class="card">
        <h2>Most visited pages</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->topPages(),
            'empty' => 'No page views yet.',
        ])
    </div>

    <div class="card">
        <h2>Language</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->byLanguage(),
            'empty' => 'No page views yet.',
            'format' => fn ($k) => $languages[$k] ?? $k,
        ])
    </div>

    <div class="card">
        <h2>Questions people open</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->faqOpens(),
            'empty' => 'Nobody has opened an FAQ yet.',
            'format' => fn ($k) => Str::limit($k, 52),
        ])
    </div>

    <div class="card">
        <h2>Enquiries by service</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->leadsBy('service'),
            'empty' => 'No enquiries in this period.',
            'format' => fn ($k) => Str::of($k)->replace('-', ' ')->title()->toString(),
        ])
    </div>

    <div class="card">
        <h2>Budget people chose</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->leadsBy('budget'),
            'empty' => 'Nobody has answered the budget question yet.',
            'format' => fn ($k) => $budgets[$k] ?? $k,
        ])
    </div>

    <div class="card">
        <h2>Timeline people chose</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->leadsBy('timeline'),
            'empty' => 'Nobody has answered the timeline question yet.',
            'format' => fn ($k) => $timelines[$k] ?? $k,
        ])
    </div>

    <div class="card">
        <h2>Which page the enquiry came from</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->leadsBy('source'),
            'empty' => 'No enquiries in this period.',
        ])
    </div>

    <div class="card">
        <h2>Campaigns bringing enquiries</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->leadsBy('utm_source'),
            'empty' => 'No enquiries arrived on a campaign link.',
        ])
    </div>

    <div class="card">
        <h2>Fields people get rejected on</h2>
        @include('admin.partials.bar-list', [
            'rows' => $analytics->formErrors(),
            'empty' => 'No validation errors — the form is not tripping anyone up.',
        ])
    </div>
</div>

<div class="card">
    <h2>Latest enquiries @if($unread) <span class="badge badge-accent">{{ $unread }} unread</span> @endif</h2>
    <table class="tbl">
        <thead>
        <tr><th>Name</th><th>Service</th><th>Language</th><th>Message</th><th>Received</th><th></th></tr>
        </thead>
        <tbody>
        @forelse($latest as $sub)
            <tr class="{{ $sub->read_at ? '' : 'unread' }}">
                <td>{{ $sub->name }}</td>
                <td>{{ $sub->service ?? '—' }}</td>
                <td><span class="badge">{{ strtoupper($sub->locale) }}</span></td>
                <td>{{ Str::limit($sub->message, 52) }}</td>
                <td>{{ $sub->created_at->diffForHumans() }}</td>
                <td><a href="{{ route('admin.inbox.show', $sub) }}">View</a></td>
            </tr>
        @empty
            <tr><td colspan="6">No enquiries yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="stat-grid" style="margin-top:16px">
    <div class="stat-card"><div class="num">{{ $counts['services'] }}</div><div class="lbl">Services</div></div>
    <div class="stat-card"><div class="num">{{ $counts['projects'] }}</div><div class="lbl">Projects</div></div>
    <div class="stat-card"><div class="num">{{ $counts['testimonials'] }}</div><div class="lbl">Testimonials</div></div>
    <div class="stat-card"><div class="num">{{ $counts['submissions'] }}</div><div class="lbl">Enquiries all-time</div></div>
</div>
@endsection
