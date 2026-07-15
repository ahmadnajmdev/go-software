@extends('admin.layout')

@section('title', $item->exists ? 'Edit project' : 'New project')

@section('content')
    <form method="POST" action="{{ $item->exists ? route('admin.projects.update', $item) : route('admin.projects.store') }}">
        @csrf
        @if($item->exists) @method('PUT') @endif

        <div class="card">
            <div class="field">
                <label for="image">Image</label>
                <input type="text" id="image" name="image" value="{{ old('image', $item->image) }}">
                @if($item->image)<img class="thumb" src="{{ media_url($item->image) }}" alt="" style="margin-top:8px">@endif
                <p class="hint">Paste URL or uploads/… path — manage files in <a href="{{ route('admin.media.index') }}">Media</a>.</p>
            </div>
            @include('admin.partials.lang-field', ['field' => 'title', 'label' => 'Title', 'required' => true])
            @include('admin.partials.lang-field', ['field' => 'category', 'label' => 'Category', 'required' => true])
        </div>

        <button type="submit" class="btn">{{ $item->exists ? 'Save changes' : 'Create project' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.projects.index') }}">Cancel</a>
    </form>
@endsection
