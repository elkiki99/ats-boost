<?php

namespace Database\Factories;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscriber>
 */
class SubscriberFactory extends Factory
{
    protected $model = Subscriber::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'mp_subscription_id' => $this->faker->uuid(),
            'mp_plan_id' => $this->faker->uuid(),
            'status' => 'authorized',
            'active' => true,
            'ends_at' => now()->addMonth(),
            'trial_ends_at' => null,
            'renews_at' => now()->addMonth(),
            'payer_email' => $this->faker->safeEmail(),
            'metadata' => [],
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (): array => [
            'ends_at' => now()->subDay(),
            'renews_at' => null,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (): array => [
            'status' => 'cancelled',
            'active' => false,
        ]);
    }

    public function onTrial(): static
    {
        return $this->state(fn (): array => [
            'trial_ends_at' => now()->addDays(7),
            'ends_at' => now()->addDays(7),
        ]);
    }
}
