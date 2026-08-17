@extends('admin.layout')

@section('title', 'Categories')
@section('subtitle', 'Used for the filter chips on the projects grid.')

@section('header-actions')
    <a class="btn" href="{{ route('admin.categories.create') }}">New category</a>
@endsection

@section('content')
    <table class="tbl">
        <thead>
        <tr><th>Name</th><th>Slug</th><th style="width:110px">Projects</th><th style="width:130px">Actions</th></tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                <td>{{ $item->tr('name') }}</td>
                <td><span class="badge">{{ $item->slug }}</span></td>
                <td>{{ $item->projects_count }}</td>
                <td>
                    <div class="row-actions">
                        <a class="btn btn-ghost btn-sm" href="{{ route('admin.categories.edit', $item) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $item) }}"
                              onsubmit="return confirm('Delete this category? Its projects stay, but become uncategorised.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No categories yet.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
