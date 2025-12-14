<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'user_id',
        'lemon_subscription_id',
        'lemon_variant_id',
        'active',
        'ends_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'ends_at' => 'datetime',
    ];
}
