<?php

declare(strict_types=1);

use App\Filament\Resources\Quotes\Pages\EditQuote;
use App\Filament\Resources\Quotes\RelationManagers\OrdersRelationManager;
use App\Models\Order;
use App\Models\Quote;
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

    $this->quote = Quote::factory()->create(['team_id' => $this->team->id]);
});

it('can render the orders relation manager', function (): void {
    livewire(OrdersRelationManager::class, [
        'ownerRecord' => $this->quote,
        'pageClass' => EditQuote::class,
    ])
        ->assertSuccessful();
});

it('can list orders for a quote', function (): void {
    $orders = Order::factory()->count(3)->create([
        'quote_id' => $this->quote->id,
        'customer_id' => $this->quote->customer_id,
        'team_id' => $this->team->id,
    ]);

    livewire(OrdersRelationManager::class, [
        'ownerRecord' => $this->quote,
        'pageClass' => EditQuote::class,
    ])
        ->assertCanSeeTableRecords($orders);
});
