@extends('admin.layout')

@section('title', 'Testimonials')

@section('header-actions')
    <a class="btn" href="{{ route('admin.testimonials.create') }}">New testimonial</a>
@endsection

@section('content')
    <table class="tbl">
        <thead>
        <tr><th>Avatar</th><th>Author</th><th>Role</th><th>Rating</th><th style="width:130px">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                <td><img class="thumb" src="{{ media_url($item->avatar) }}" alt=""></td>
                <td>{{ $item->author }}</td>
                <td>{{ $item->tr('role') }}</td>
                <td>{{ str_repeat('★', $item->rating) }}</td>
                <td>
                    <div class="row-actions">
                        <a class="btn btn-ghost btn-sm" href="{{ route('admin.testimonials.edit', $item) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.testimonials.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No testimonials yet.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
