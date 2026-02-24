<?php

declare(strict_types=1);

use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\RelationManagers\ShipmentsRelationManager;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->user->teams()->attach($this->team);

    $this->actingAs($this->user);

    Filament::setTenant($this->team);
    Filament::bootCurrentPanel();

    $this->order = Order::factory()->create(['team_id' => $this->team->id]);
});

it('can render the shipments relation manager', function (): void {
    livewire(ShipmentsRelationManager::class, [
        'ownerRecord' => $this->order,
        'pageClass' => EditOrder::class,
    ])
        ->assertSuccessful();
});

it('can list shipments for an order', function (): void {
    $shipments = Shipment::factory()->count(3)->create([
        'order_id' => $this->order->id,
        'customer_id' => $this->order->customer_id,
    ]);

    livewire(ShipmentsRelationManager::class, [
        'ownerRecord' => $this->order,
        'pageClass' => EditOrder::class,
    ])
        ->assertCanSeeTableRecords($shipments);
});
