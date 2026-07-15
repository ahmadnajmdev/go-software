@extends('admin.layout')

@section('title', 'Submission from ' . $item->name)

@section('header-actions')
    <a class="btn btn-ghost" href="{{ route('admin.inbox.index') }}">← Back to inbox</a>
@endsection

@section('content')
    <div class="card" style="max-width:720px">
        <table class="tbl">
            <tr><th style="width:140px">Name</th><td>{{ $item->name }}</td></tr>
            <tr><th>Email</th><td><a href="mailto:{{ $item->email }}">{{ $item->email }}</a></td></tr>
            <tr><th>Phone</th><td>{{ $item->phone ?? '—' }}</td></tr>
            <tr><th>Service</th><td>{{ $item->service ?? '—' }}</td></tr>
            <tr><th>Language</th><td><span class="badge">{{ strtoupper($item->locale) }}</span></td></tr>
            <tr><th>Received</th><td>{{ $item->created_at->format('Y-m-d H:i') }} ({{ $item->created_at->diffForHumans() }})</td></tr>
            <tr><th>Message</th><td style="white-space:pre-wrap">{{ $item->message }}</td></tr>
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
