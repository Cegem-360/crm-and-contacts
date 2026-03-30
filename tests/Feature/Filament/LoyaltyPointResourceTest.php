<?php

declare(strict_types=1);

use App\Filament\Resources\LoyaltyPoints\Pages\ListLoyaltyPoints;
use App\Models\Customer;
use App\Models\LoyaltyPoint;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Permission;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->user->teams()->attach($this->team);

    Permission::query()->firstOrCreate(['name' => 'view_any_loyalty::point']);
    Permission::query()->firstOrCreate(['name' => 'view_loyalty::point']);

    $this->user->givePermissionTo([
        'view_any_loyalty::point',
        'view_loyalty::point',
    ]);

    $this->actingAs($this->user);

    Filament::setTenant($this->team);
    Filament::bootCurrentPanel();
});

it('can render loyalty points list page', function (): void {
    livewire(ListLoyaltyPoints::class)
        ->assertSuccessful();
});

it('can list loyalty point transactions', function (): void {
    $customer = Customer::factory()->create(['team_id' => $this->team->id]);
    $transactions = LoyaltyPoint::factory()->count(3)->create([
        'team_id' => $this->team->id,
        'customer_id' => $customer->id,
    ]);

    livewire(ListLoyaltyPoints::class)
        ->assertCanSeeTableRecords($transactions);
});
