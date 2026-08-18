<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $casts = ['role' => 'array', 'quote' => 'array', 'result' => 'array'];

    protected array $translatable = ['role', 'quote', 'result'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    /** A testimonial is only usable once someone real is quoted. */
    public function isPublishable(): bool
    {
        return filled($this->author) && filled($this->tr('quote'));
    }
}
