<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $casts = ['category' => 'array', 'title' => 'array'];

    protected array $translatable = ['category', 'title'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }
}
