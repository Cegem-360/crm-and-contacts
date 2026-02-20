<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Quote;
use App\Models\QuoteVersion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteVersion>
 */
final class QuoteVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quote_id' => Quote::factory(),
            'version_number' => fake()->numberBetween(1, 10),
            'snapshot' => [
                'quote' => ['quote_number' => 'QT-'.fake()->numerify('######')],
                'items' => [],
            ],
            'changes_log' => ['initial' => true],
            'pdf_path' => null,
            'created_by' => User::factory(),
        ];
    }
}
