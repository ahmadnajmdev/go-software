@extends('admin.layout')

@section('title', 'Projects')

@section('header-actions')
    <a class="btn" href="{{ route('admin.projects.create') }}">New project</a>
@endsection

@section('content')
    <table class="tbl">
        <thead>
        <tr><th>Image</th><th>Title</th><th>Category</th><th style="width:130px">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                <td><img class="thumb" src="{{ media_url($item->image) }}" alt=""></td>
                <td>{{ $item->tr('title') }}</td>
                <td>@if($item->category)<span class="badge">{{ $item->category->tr('name') }}</span>@else<span class="badge">uncategorised</span>@endif</td>
                <td>
                    <div class="row-actions">
                        <a class="btn btn-ghost btn-sm" href="{{ route('admin.projects.edit', $item) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.projects.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No projects yet.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
