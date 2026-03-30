<?php

declare(strict_types=1);

use App\Enums\LoyaltyPointSource;
use App\Enums\LoyaltyTransactionType;
use App\Models\Customer;
use App\Models\LoyaltyPoint;
use App\Models\Team;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->customer = Customer::factory()->create(['team_id' => $this->team->id]);
});

it('can create a loyalty point transaction', function (): void {
    $transaction = LoyaltyPoint::factory()->create([
        'team_id' => $this->team->id,
        'customer_id' => $this->customer->id,
        'points' => 100,
        'type' => LoyaltyTransactionType::Earned,
        'source' => LoyaltyPointSource::OrderCompleted,
        'description' => 'Test transaction',
        'balance_after' => 100,
    ]);

    expect($transaction)
        ->points->toBe(100)
        ->type->toBe(LoyaltyTransactionType::Earned)
        ->source->toBe(LoyaltyPointSource::OrderCompleted)
        ->description->toBe('Test transaction')
        ->balance_after->toBe(100);
});

it('belongs to a customer', function (): void {
    $transaction = LoyaltyPoint::factory()->create([
        'team_id' => $this->team->id,
        'customer_id' => $this->customer->id,
    ]);

    expect($transaction->customer)->toBeInstanceOf(Customer::class)
        ->and($transaction->customer->id)->toBe($this->customer->id);
});

it('belongs to a team', function (): void {
    $transaction = LoyaltyPoint::factory()->create([
        'team_id' => $this->team->id,
        'customer_id' => $this->customer->id,
    ]);

    expect($transaction->team)->toBeInstanceOf(Team::class)
        ->and($transaction->team->id)->toBe($this->team->id);
});

it('casts type and source to enums', function (): void {
    $transaction = LoyaltyPoint::factory()->create([
        'team_id' => $this->team->id,
        'customer_id' => $this->customer->id,
        'type' => LoyaltyTransactionType::Earned,
        'source' => LoyaltyPointSource::ManualAdjustment,
    ]);

    expect($transaction->type)->toBeInstanceOf(LoyaltyTransactionType::class)
        ->and($transaction->source)->toBeInstanceOf(LoyaltyPointSource::class);
});
