@extends('admin.layout')

@section('title', $item->exists ? 'Edit category' : 'New category')

@section('content')
    <form method="POST"
          action="{{ $item->exists ? route('admin.categories.update', $item) : route('admin.categories.store') }}">
        @csrf
        @if($item->exists) @method('PUT') @endif

        <div class="card">
            @include('admin.partials.lang-field', ['field' => 'name', 'label' => 'Name', 'required' => true])

            <div class="field">
                <label for="slug">Slug <span style="font-weight:400;color:#7b858f">(optional)</span></label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $item->slug) }}" placeholder="mobile-app">
                <p class="hint">Used in the chip filter and the <code>?category=</code> link on the projects page.
                    Leave blank to generate it from the English name.</p>
            </div>
        </div>

        <button type="submit" class="btn">{{ $item->exists ? 'Save changes' : 'Create category' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.categories.index') }}">Cancel</a>
    </form>
@endsection
