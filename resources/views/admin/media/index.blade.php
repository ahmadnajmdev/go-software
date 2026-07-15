@extends('admin.layout')

@section('title', 'Media')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
            @csrf
            <input type="file" name="file" accept="image/*" required style="max-width:340px">
            <button type="submit" class="btn">Upload</button>
            <span class="hint" style="margin:0">jpg / png / webp, max 4 MB.</span>
        </form>
    </div>

    <div class="grid-3">
        @forelse($items as $item)
            <div class="card" style="margin-bottom:0;padding:12px">
                <img src="{{ $item->url() }}" alt="{{ $item->original_name }}" loading="lazy"
                     style="width:100%;height:120px;object-fit:cover;border-radius:8px">
                <p style="margin:10px 0 6px;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $item->original_name }}">{{ $item->original_name }}</p>
                <input type="text" readonly value="{{ $item->path }}" onclick="this.select()" style="font-size:12px;padding:6px">
                <form method="POST" action="{{ route('admin.media.destroy', $item) }}" onsubmit="return confirm('Delete?')" style="margin-top:8px">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        @empty
            <p>No uploads yet.</p>
        @endforelse
    </div>
@endsection
