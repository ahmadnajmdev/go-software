<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $casts = ['name' => 'array'];

    protected array $translatable = ['name'];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }
}
