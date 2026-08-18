<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $casts = [
        'title' => 'array', 'outcome' => 'array', 'problem' => 'array',
        'solution' => 'array', 'result' => 'array', 'quote' => 'array',
        'screenshots' => 'array',
    ];

    protected array $translatable = ['title', 'outcome', 'problem', 'solution', 'result', 'quote'];

    /** What we built — the secondary filter. */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Who the client is — the primary filter. */
    public function industry(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'industry_id');
    }

    /**
     * A detail page is worth linking to once it says more than the tile does.
     * Until then the tile keeps its old behaviour, so a half-filled project
     * never sends anyone to an empty page.
     */
    public function hasStory(): bool
    {
        return filled($this->slug) && (
            filled($this->tr('problem')) || filled($this->tr('solution')) || filled($this->tr('result'))
        );
    }

    public function detailUrl(): ?string
    {
        return $this->hasStory() ? gs_route('projects/'.$this->slug) : null;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }
}
