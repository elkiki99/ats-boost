<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'user_id',
        'lemon_subscription_id',
        'lemon_variant_id',
        'status',
        'active',
        'ends_at',
    ];

    protected $casts = [
        'active'   => 'boolean',
        'ends_at'  => 'datetime',
        'renews_at' => 'datetime',
    ];

    public function hasAccess(): bool
    {
        return $this->active
            || (
                ! $this->active
                && $this->ends_at
                && now()->lt($this->ends_at)
            );
    }
}
