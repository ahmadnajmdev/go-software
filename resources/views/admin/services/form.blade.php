@extends('admin.layout')

@section('title', $item->exists ? 'Edit service' : 'New service')

@section('content')
    <form method="POST" action="{{ $item->exists ? route('admin.services.update', $item) : route('admin.services.store') }}">
        @csrf
        @if($item->exists) @method('PUT') @endif

        <div class="card">
            <div class="field">
                <label for="image">Image</label>
                <input type="text" id="image" name="image" value="{{ old('image', $item->image) }}">
                @if($item->image)<img class="thumb" src="{{ media_url($item->image) }}" alt="" style="margin-top:8px">@endif
                <p class="hint">Paste URL or uploads/… path — manage files in <a href="{{ route('admin.media.index') }}">Media</a>.</p>
            </div>
            <div class="field">
                <label for="tag">Tag</label>
                <input type="text" id="tag" name="tag" value="{{ old('tag', $item->tag) }}">
            </div>
            @include('admin.partials.lang-field', ['field' => 'title', 'label' => 'Title', 'required' => true])
            @include('admin.partials.lang-field', ['field' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => true])
        </div>

        <button type="submit" class="btn">{{ $item->exists ? 'Save changes' : 'Create service' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.services.index') }}">Cancel</a>
    </form>
@endsection
