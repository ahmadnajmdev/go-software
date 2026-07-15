<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $guarded = [];

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    protected static function booted(): void
    {
        static::deleted(fn (Media $media) => Storage::disk('public')->delete($media->path));
    }
}
