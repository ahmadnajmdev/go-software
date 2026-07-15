<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $guarded = [];

    protected $casts = ['visible' => 'boolean'];

    protected static function booted(): void
    {
        static::saved(fn () => cache()->forget('gs.sections'));
        static::deleted(fn () => cache()->forget('gs.sections'));
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }
}
