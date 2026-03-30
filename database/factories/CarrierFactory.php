<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Carrier;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Carrier> */
final class CarrierFactory extends Factory
{
    protected $model = Carrier::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name' => fake()->randomElement(['DPD', 'GLS', 'FoxPost', 'MPL', 'DHL', 'FedEx', 'UPS', 'TNT', 'Sprinter']),
            'is_active' => true,
        ];
    }
}
