<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceItem>
 */
final class InvoiceItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->randomFloat(2, 1, 100);
        $unitPrice = fake()->randomFloat(2, 10, 1000);
        $discountAmount = fake()->randomFloat(2, 0, $unitPrice * $quantity * 0.1);
        $taxRate = fake()->randomElement([0, 5, 18, 27]);
        $subtotal = ($unitPrice * $quantity) - $discountAmount;
        $total = $subtotal + ($subtotal * ($taxRate / 100));

        return [
            'invoice_id' => Invoice::factory(),
            'product_id' => null,
            'description' => fake()->sentence(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount_amount' => $discountAmount,
            'tax_rate' => $taxRate,
            'total' => $total,
        ];
    }
}
