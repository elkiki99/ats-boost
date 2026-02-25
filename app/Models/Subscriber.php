<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscriber extends Model
{
    protected $fillable = [
        'user_id',
        'mp_subscription_id',
        'mp_plan_id',
        'status',
        'active',
        'ends_at',
        'trial_ends_at',
        'renews_at',
        'payer_email',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'ends_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'renews_at' => 'datetime',
            'metadata' => 'json',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasAccess(): bool
    {
        return $this->ends_at?->isFuture() ?? false;
    }
}
