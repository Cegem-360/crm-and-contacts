<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LoyaltyLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoyaltyLevel>
 */
final class LoyaltyLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Bronze', 'Silver', 'Gold', 'Platinum']),
            'minimum_points' => fake()->numberBetween(0, 10000),
            'discount_percentage' => fake()->randomFloat(2, 0, 25),
            'color' => fake()->randomElement(['bronze', 'silver', 'gold', 'platinum']),
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }

    public function bronze(): static
    {
        return $this->state(fn (array $attributes): array => [
            'name' => 'Bronze',
            'minimum_points' => 0,
            'discount_percentage' => 0,
            'color' => 'bronze',
            'sort_order' => 1,
        ]);
    }

    public function silver(): static
    {
        return $this->state(fn (array $attributes): array => [
            'name' => 'Silver',
            'minimum_points' => 1000,
            'discount_percentage' => 5,
            'color' => 'silver',
            'sort_order' => 2,
        ]);
    }

    public function gold(): static
    {
        return $this->state(fn (array $attributes): array => [
            'name' => 'Gold',
            'minimum_points' => 5000,
            'discount_percentage' => 10,
            'color' => 'gold',
            'sort_order' => 3,
        ]);
    }

    public function platinum(): static
    {
        return $this->state(fn (array $attributes): array => [
            'name' => 'Platinum',
            'minimum_points' => 10000,
            'discount_percentage' => 15,
            'color' => 'platinum',
            'sort_order' => 4,
        ]);
    }
}
