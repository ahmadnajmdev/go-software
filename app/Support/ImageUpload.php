<?php

namespace App\Support;

use App\Models\Media;
use Illuminate\Http\UploadedFile;

class ImageUpload
{
    /** Validation rules shared by the Media page and every admin image field. */
    public const RULES = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'];

    /**
     * Store an uploaded image on the public disk and register it in the media
     * library so it shows up in every picker. Returns the storage path.
     */
    public static function store(UploadedFile $file): Media
    {
        $path = $file->store('uploads/'.now()->format('Y/m'), 'public');

        return Media::create([
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }
}
