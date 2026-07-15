<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $guarded = [];

    protected $casts = ['read_at' => 'datetime'];

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
