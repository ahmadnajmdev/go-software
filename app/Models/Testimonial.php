<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $casts = ['role' => 'array', 'quote' => 'array'];

    protected array $translatable = ['role', 'quote'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }
}
