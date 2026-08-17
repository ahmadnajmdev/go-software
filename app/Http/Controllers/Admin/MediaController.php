<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Support\ImageUpload;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $items = Media::latest()->get();

        if ($request->expectsJson()) {
            return response()->json([
                'items' => $items->map(fn (Media $m) => [
                    'id' => $m->id, 'path' => $m->path, 'url' => $m->url(), 'name' => $m->original_name,
                ]),
            ]);
        }

        return view('admin.media.index', ['items' => $items]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', ...ImageUpload::RULES],
        ]);

        $media = ImageUpload::store($request->file('file'));

        if ($request->expectsJson()) {
            return response()->json(['id' => $media->id, 'path' => $media->path, 'url' => $media->url()]);
        }

        return back()->with('ok', 'Image uploaded.');
    }

    public function destroy(Media $media)
    {
        $media->delete();

        return back()->with('ok', 'Image deleted.');
    }
}
