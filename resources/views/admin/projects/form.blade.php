@extends('admin.layout')

@section('title', $item->exists ? 'Edit project' : 'New project')

@section('content')
    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.projects.update', $item) : route('admin.projects.store') }}">
        @csrf
        @if($item->exists) @method('PUT') @endif

        <div class="card">
            @include('admin.partials.image-field', ['value' => $item->image, 'library' => $media])

            <div class="field">
                <label>Image fit</label>
                @php($fit = old('fit', $item->fit ?? 'cover'))
                <div class="radio-row">
                    <label><input type="radio" name="fit" value="cover" @checked($fit === 'cover')> Fill the tile</label>
                    <label><input type="radio" name="fit" value="contain" @checked($fit === 'contain')> Show the whole image</label>
                </div>
                <p class="hint">Tiles are tall and narrow. <strong>Fill</strong> crops to fit — right for photos.
                    <strong>Show the whole image</strong> never crops, and blurs a copy of the image behind it to
                    fill the tile — use it for logos, wordmarks and screenshots that get cut off.</p>
            </div>
            @include('admin.partials.lang-field', ['field' => 'title', 'label' => 'Title', 'required' => true])

            <div class="field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">— Uncategorised —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected((int) old('category_id', $item->category_id) === $category->id)>
                            {{ $category->tr('name') }}
                        </option>
                    @endforeach
                </select>
                <p class="hint">Drives the filter chips on the projects grid.
                    Manage the list in <a href="{{ route('admin.categories.index') }}">Categories</a>.</p>
            </div>
        </div>

        <button type="submit" class="btn">{{ $item->exists ? 'Save changes' : 'Create project' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.projects.index') }}">Cancel</a>
    </form>
@endsection
