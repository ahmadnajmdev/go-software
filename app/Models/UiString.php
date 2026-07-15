<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class UiString extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $casts = ['value' => 'array'];

    protected array $translatable = ['value'];

    protected static function booted(): void
    {
        static::saved(fn () => cache()->forget('gs.strings'));
        static::deleted(fn () => cache()->forget('gs.strings'));
    }
}
