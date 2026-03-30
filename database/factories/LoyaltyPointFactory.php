<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LoyaltyPointSource;
use App\Enums\LoyaltyTransactionType;
use App\Models\Customer;
use App\Models\LoyaltyPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoyaltyPoint>
 */
final class LoyaltyPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'points' => fake()->numberBetween(1, 500),
            'type' => LoyaltyTransactionType::Earned,
            'source' => fake()->randomElement(LoyaltyPointSource::class),
            'description' => fake()->optional()->sentence(),
            'balance_after' => fake()->numberBetween(0, 10000),
        ];
    }

    public function earned(int $points = 100): static
    {
        return $this->state(fn (array $attributes): array => [
            'points' => $points,
            'type' => LoyaltyTransactionType::Earned,
        ]);
    }

    public function spent(int $points = 100): static
    {
        return $this->state(fn (array $attributes): array => [
            'points' => -$points,
            'type' => LoyaltyTransactionType::Spent,
        ]);
    }

    public function adjusted(int $points = 0): static
    {
        return $this->state(fn (array $attributes): array => [
            'points' => $points,
            'type' => LoyaltyTransactionType::Adjusted,
            'source' => LoyaltyPointSource::ManualAdjustment,
        ]);
    }
}
