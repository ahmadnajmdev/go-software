<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    public const UPDATED_AT = null;

    /**
     * The events the site is allowed to record. Anything else posted to the
     * collector is dropped — the endpoint is public, so the vocabulary is
     * fixed here rather than trusted from the request.
     */
    public const NAMES = [
        'page_view',
        'cta_click',
        'whatsapp_click',
        'phone_click',
        'email_click',
        'form_start',
        'form_step_complete',
        'form_submit',
        'form_error',
        'project_view',
        'faq_open',
    ];

    protected $guarded = [];

    protected $casts = ['params' => 'array', 'created_at' => 'datetime'];

    public function scopeSince(Builder $query, \DateTimeInterface $from): Builder
    {
        return $query->where('created_at', '>=', $from);
    }

    public function scopeBetween(Builder $query, \DateTimeInterface $from, \DateTimeInterface $to): Builder
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function scopeNamed(Builder $query, string ...$names): Builder
    {
        return $query->whereIn('name', $names);
    }
}
