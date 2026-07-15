<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $casts = ['title' => 'array', 'description' => 'array'];

    protected array $translatable = ['title', 'description'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }
}
