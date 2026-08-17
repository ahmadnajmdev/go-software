@extends('admin.layout')

@section('title', $item->exists ? 'Edit project' : 'New project')

@section('content')
    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.projects.update', $item) : route('admin.projects.store') }}">
        @csrf
        @if($item->exists) @method('PUT') @endif

        <div class="card">
            @include('admin.partials.image-field', ['value' => $item->image, 'library' => $media])
            @include('admin.partials.lang-field', ['field' => 'title', 'label' => 'Title', 'required' => true])
            @include('admin.partials.lang-field', ['field' => 'category', 'label' => 'Category', 'required' => true])
        </div>

        <button type="submit" class="btn">{{ $item->exists ? 'Save changes' : 'Create project' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.projects.index') }}">Cancel</a>
    </form>
@endsection
