@extends('admin.layout')

@section('title', $item->exists ? 'Edit testimonial' : 'New testimonial')

@section('content')
    <form method="POST" action="{{ $item->exists ? route('admin.testimonials.update', $item) : route('admin.testimonials.store') }}">
        @csrf
        @if($item->exists) @method('PUT') @endif

        <div class="card">
            <div class="field">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" value="{{ old('author', $item->author) }}" required>
            </div>
            <div class="field">
                <label for="rating">Rating (1–5)</label>
                <input type="number" id="rating" name="rating" min="1" max="5" value="{{ old('rating', $item->rating ?? 5) }}" required style="max-width:120px">
            </div>
            <div class="field">
                <label for="avatar">Avatar</label>
                <input type="text" id="avatar" name="avatar" value="{{ old('avatar', $item->avatar) }}">
                @if($item->avatar)<img class="thumb" src="{{ media_url($item->avatar) }}" alt="" style="margin-top:8px">@endif
                <p class="hint">Paste URL or uploads/… path — manage files in <a href="{{ route('admin.media.index') }}">Media</a>.</p>
            </div>
            @include('admin.partials.lang-field', ['field' => 'role', 'label' => 'Role', 'required' => true])
            @include('admin.partials.lang-field', ['field' => 'quote', 'label' => 'Quote', 'type' => 'textarea', 'required' => true])
        </div>

        <button type="submit" class="btn">{{ $item->exists ? 'Save changes' : 'Create testimonial' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.testimonials.index') }}">Cancel</a>
    </form>
@endsection
