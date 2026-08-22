<?php

namespace App\Models;

use Database\Factories\SubscriberFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Suscripción de Mercado Pago asociada a un usuario.
 *
 * @property bool $active
 * @property Carbon|null $ends_at
 * @property Carbon|null $trial_ends_at
 * @property Carbon|null $renews_at
 */
class Subscriber extends Model
{
    /** @use HasFactory<SubscriberFactory> */
    use HasFactory;

    /**
     * Estados de Mercado Pago que habilitan el acceso.
     */
    public const ACTIVE_STATUSES = ['authorized', 'active'];

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

    /**
     * Única definición de "esta suscripción da acceso".
     *
     * Antes esta condición estaba escrita a mano en el modelo User, en dos
     * middlewares y en el componente de suscripciones, con criterios que ya
     * habían empezado a divergir entre sí.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('active', true)
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->where('ends_at', '>', now());
    }

    public function isActive(): bool
    {
        return $this->active
            && in_array($this->status, self::ACTIVE_STATUSES, true)
            && ($this->ends_at?->isFuture() ?? false);
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at?->isFuture() ?? false;
    }

    /**
     * Cancelada pero todavía con acceso hasta el fin del período pago.
     */
    public function isCancelledButActive(): bool
    {
        return ! $this->active && ($this->ends_at?->isFuture() ?? false);
    }

    public function daysRemaining(): int
    {
        if ($this->ends_at === null || $this->ends_at->isPast()) {
            return 0;
        }

        return (int) now()->diffInDays($this->ends_at, absolute: true);
    }
}
