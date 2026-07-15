@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="stat-grid">
        <div class="stat-card"><div class="num">{{ $counts['services'] }}</div><div class="lbl">Services</div></div>
        <div class="stat-card"><div class="num">{{ $counts['projects'] }}</div><div class="lbl">Projects</div></div>
        <div class="stat-card"><div class="num">{{ $counts['testimonials'] }}</div><div class="lbl">Testimonials</div></div>
        <div class="stat-card">
            <div class="num">{{ $counts['submissions'] }}</div>
            <div class="lbl">Submissions @if($unread) <span class="badge badge-accent">{{ $unread }} unread</span> @endif</div>
        </div>
    </div>

    <div class="card">
        <h2>Latest submissions</h2>
        <table class="tbl">
            <thead>
            <tr><th>Name</th><th>Email</th><th>Message</th><th>Received</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($latest as $sub)
                <tr class="{{ $sub->read_at ? '' : 'unread' }}">
                    <td>{{ $sub->name }}</td>
                    <td>{{ $sub->email }}</td>
                    <td>{{ Str::limit($sub->message, 60) }}</td>
                    <td>{{ $sub->created_at->diffForHumans() }}</td>
                    <td><a href="{{ route('admin.inbox.show', $sub) }}">View</a></td>
                </tr>
            @empty
                <tr><td colspan="5">No submissions yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
