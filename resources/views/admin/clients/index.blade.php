@extends('admin.layout')

@section('title', 'Clients')

@section('header-actions')
    <a class="btn" href="{{ route('admin.clients.create') }}">New client</a>
@endsection

@section('content')
    <p class="hint" style="margin:-6px 0 16px">Logos scroll in the marquee above the testimonials. Clients without a
        logo fall back to their name as text. Transparent PNG or SVG-exported PNG works best.</p>

    <table class="tbl">
        <thead>
        <tr><th style="width:150px">Logo</th><th>Name</th><th>Website</th><th style="width:130px">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                <td>
                    @if($item->logo)
                        <img src="{{ media_url($item->logo) }}" alt="{{ $item->name }}"
                             style="height:32px;max-width:130px;object-fit:contain">
                    @else
                        <span class="badge">no logo</span>
                    @endif
                </td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->url ? parse_url($item->url, PHP_URL_HOST) : '—' }}</td>
                <td>
                    <div class="row-actions">
                        <a class="btn btn-ghost btn-sm" href="{{ route('admin.clients.edit', $item) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.clients.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No clients yet.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
