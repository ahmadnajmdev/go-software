@extends('admin.layout')

@section('title', 'Inbox')

@section('content')
    <table class="tbl">
        <thead>
        <tr><th></th><th>Name</th><th>Email</th><th>Service</th><th>Message</th><th>Lang</th><th>Date</th><th style="width:200px">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            <tr class="{{ $item->read_at ? '' : 'unread' }}">
                <td><span class="dot {{ $item->read_at ? '' : 'dot-accent' }}"></span></td>
                <td>{{ $item->name }}</td>
                <td><a href="mailto:{{ $item->email }}">{{ $item->email }}</a></td>
                <td>{{ $item->service ?? '—' }}</td>
                <td>{{ Str::limit($item->message, 60) }}</td>
                <td><span class="badge">{{ strtoupper($item->locale) }}</span></td>
                <td>{{ $item->created_at->diffForHumans() }}</td>
                <td>
                    <div class="row-actions">
                        <a class="btn btn-ghost btn-sm" href="{{ route('admin.inbox.show', $item) }}">View</a>
                        <form method="POST" action="{{ route('admin.inbox.toggle', $item) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-ghost btn-sm">{{ $item->read_at ? 'Mark unread' : 'Mark read' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.inbox.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="8">Inbox is empty.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $items->links() }}
@endsection
