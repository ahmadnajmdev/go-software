@extends('admin.layout')

@section('title', $item->exists ? 'Edit client' : 'New client')

@section('content')
    <form method="POST" enctype="multipart/form-data"
          action="{{ $item->exists ? route('admin.clients.update', $item) : route('admin.clients.store') }}">
        @csrf
        @if($item->exists) @method('PUT') @endif

        <div class="card">
            @include('admin.partials.image-field', [
                'field' => 'logo',
                'label' => 'Logo',
                'value' => $item->logo,
                'library' => $media,
                'fit' => 'contain',
            ])

            <div class="field">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required>
                <p class="hint">Shown as the image alt text, and as the marquee item itself when there is no logo.</p>
            </div>

            <div class="field">
                <label for="url">Website <span style="font-weight:400;color:#66757f">(optional)</span></label>
                <input type="text" id="url" name="url" value="{{ old('url', $item->url) }}" placeholder="https://example.com">
                <p class="hint">If set, the logo links to this address in a new tab.</p>
            </div>
        </div>

        <button type="submit" class="btn">{{ $item->exists ? 'Save changes' : 'Create client' }}</button>
        <a class="btn btn-ghost" href="{{ route('admin.clients.index') }}">Cancel</a>
    </form>
@endsection
