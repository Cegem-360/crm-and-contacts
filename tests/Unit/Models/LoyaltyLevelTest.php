<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\LoyaltyLevel;
use App\Models\Team;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
});

it('can create a loyalty level', function (): void {
    $level = LoyaltyLevel::factory()->create([
        'team_id' => $this->team->id,
        'name' => 'Gold',
        'minimum_points' => 5000,
        'discount_percentage' => 10,
        'sort_order' => 3,
        'is_active' => true,
    ]);

    expect($level)
        ->name->toBe('Gold')
        ->minimum_points->toBe(5000)
        ->discount_percentage->toBe('10.00')
        ->sort_order->toBe(3)
        ->is_active->toBeTrue();
});

it('belongs to a team', function (): void {
    $level = LoyaltyLevel::factory()->create(['team_id' => $this->team->id]);

    expect($level->team)->toBeInstanceOf(Team::class)
        ->and($level->team->id)->toBe($this->team->id);
});

it('has many customers', function (): void {
    $level = LoyaltyLevel::factory()->create(['team_id' => $this->team->id]);

    Customer::factory()->count(3)->create([
        'team_id' => $this->team->id,
        'loyalty_level_id' => $level->id,
    ]);

    expect($level->customers)->toHaveCount(3);
});

it('casts attributes correctly', function (): void {
    $level = LoyaltyLevel::factory()->create([
        'team_id' => $this->team->id,
        'is_active' => true,
        'minimum_points' => 1000,
        'sort_order' => 2,
    ]);

    expect($level->is_active)->toBeBool()
        ->and($level->minimum_points)->toBeInt()
        ->and($level->sort_order)->toBeInt();
});
