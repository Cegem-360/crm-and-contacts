<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DiscountValueType;
use App\Enums\PriceRuleType;
use App\Models\Customer;
use App\Models\PriceRule;
use App\Models\Product;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PriceRule> */
final class PriceRuleFactory extends Factory
{
    protected $model = PriceRule::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name' => fake()->words(3, true),
            'rule_type' => fake()->randomElement(PriceRuleType::cases()),
            'conditions' => [],
            'discount_type' => fake()->randomElement(DiscountValueType::cases()),
            'discount_value' => fake()->randomFloat(2, 1, 20),
            'priority' => fake()->numberBetween(0, 100),
            'combinable' => false,
            'is_active' => true,
        ];
    }

    public function quantity(float $minQty = 10, float $discountPercent = 5): static
    {
        return $this->state(fn (): array => [
            'rule_type' => PriceRuleType::Quantity,
            'conditions' => ['min_qty' => $minQty],
            'discount_type' => DiscountValueType::Percentage,
            'discount_value' => $discountPercent,
        ]);
    }

    public function valueThreshold(float $minValue = 100000, float $discountPercent = 3): static
    {
        return $this->state(fn (): array => [
            'rule_type' => PriceRuleType::ValueThreshold,
            'conditions' => ['min_value' => $minValue],
            'discount_type' => DiscountValueType::Percentage,
            'discount_value' => $discountPercent,
        ]);
    }

    public function customerSpecific(?int $customerId = null, ?int $productId = null): static
    {
        return $this->state(fn (): array => [
            'rule_type' => PriceRuleType::CustomerSpecific,
            'customer_id' => $customerId ?? Customer::factory(),
            'product_id' => $productId ?? Product::factory(),
            'discount_type' => DiscountValueType::Fixed,
        ]);
    }

    public function seasonal(string $validFrom, string $validTo): static
    {
        return $this->state(fn (): array => [
            'rule_type' => PriceRuleType::Seasonal,
            'conditions' => ['valid_from' => $validFrom, 'valid_to' => $validTo],
        ]);
    }

    public function combinable(): static
    {
        return $this->state(fn (): array => [
            'combinable' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'is_active' => false,
        ]);
    }
}
