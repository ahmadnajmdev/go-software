@extends('admin.layout')

@section('title', 'Submission from ' . $item->name)

@section('header-actions')
    <a class="btn btn-ghost" href="{{ route('admin.inbox.index') }}">← Back to inbox</a>
@endsection

@section('content')
    @php
        // Blade's inline @php() cannot parse an array literal, so these live
        // in a block.
        $budgets = [
            'under-3k' => 'Under $3,000',
            '3k-8k' => '$3,000 – 8,000',
            '8k-20k' => '$8,000 – 20,000',
            '20k-plus' => '$20,000+',
            'unsure' => 'Not sure yet',
        ];
        $timelines = [
            'asap' => 'As soon as possible',
            '1-3-months' => '1–3 months',
            '3-6-months' => '3–6 months',
            'exploring' => 'Just exploring',
        ];
    @endphp
    <div class="card" style="max-width:720px">
        <table class="tbl">
            <tr><th style="width:140px">Name</th><td>{{ $item->name }}</td></tr>
            <tr><th>Email</th><td><a href="mailto:{{ $item->email }}">{{ $item->email }}</a></td></tr>
            <tr><th>Phone</th><td>{{ $item->phone ?? '—' }}</td></tr>
            <tr><th>Company</th><td>{{ $item->company ?? '—' }}</td></tr>
            <tr><th>Service</th><td>{{ $item->service ?? '—' }}</td></tr>
            {{-- The qualifying answers. "Not sure yet" and "Just exploring" are
                 real answers, not blanks — they say the lead is early, not cold. --}}
            <tr><th>Budget</th><td>{{ $budgets[$item->budget] ?? '—' }}</td></tr>
            <tr><th>Timeline</th><td>{{ $timelines[$item->timeline] ?? '—' }}</td></tr>
            <tr><th>Language</th><td><span class="badge">{{ strtoupper($item->locale) }}</span></td></tr>
            <tr><th>Received</th><td>{{ $item->created_at->format('Y-m-d H:i') }} ({{ $item->created_at->diffForHumans() }})</td></tr>
            <tr><th>Message</th><td style="white-space:pre-wrap">{{ $item->message }}</td></tr>
        </table>

        {{-- Where the lead came from: which page asked, and which campaign
             brought them to the site in the first place. --}}
        @php
            $campaign = array_filter([
                'Source' => $item->utm_source, 'Medium' => $item->utm_medium,
                'Campaign' => $item->utm_campaign, 'Term' => $item->utm_term,
                'Content' => $item->utm_content,
            ]);
        @endphp
        <h3 style="margin:22px 0 8px;font-size:14px;letter-spacing:.04em;text-transform:uppercase;opacity:.6">Attribution</h3>
        <table class="tbl">
            <tr><th style="width:140px">Page</th><td>{{ $item->source ?? '—' }}</td></tr>
            <tr><th>Campaign</th><td>
                @forelse ($campaign as $label => $value)
                    <span class="badge">{{ $label }}: {{ $value }}</span>
                @empty
                    —
                @endforelse
            </td></tr>
            <tr><th>IP</th><td>{{ $item->ip ?? '—' }}</td></tr>
            <tr><th>Device</th><td style="word-break:break-all;font-size:12px;opacity:.75">{{ $item->user_agent ?? '—' }}</td></tr>
        </table>

        <div class="row-actions" style="margin-top:18px">
            <a class="btn" href="mailto:{{ $item->email }}?subject=Re: your message to GoSoftware">Reply by email</a>
            <form method="POST" action="{{ route('admin.inbox.toggle', $item) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-ghost">{{ $item->read_at ? 'Mark unread' : 'Mark read' }}</button>
            </form>
            <form method="POST" action="{{ route('admin.inbox.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
@endsection
