<?php

declare(strict_types=1);

use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\RelationManagers\CommunicationsRelationManager;
use App\Filament\Resources\Customers\RelationManagers\ComplaintsRelationManager;
use App\Filament\Resources\Customers\RelationManagers\InteractionsRelationManager;
use App\Filament\Resources\Customers\RelationManagers\InvoicesRelationManager;
use App\Filament\Resources\Customers\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\Customers\RelationManagers\TasksRelationManager;
use App\Models\Communication;
use App\Models\Complaint;
use App\Models\Customer;
use App\Models\Interaction;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Task;
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

    $this->customer = Customer::factory()->create(['team_id' => $this->team->id]);
});

it('can render the orders relation manager', function (): void {
    livewire(OrdersRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertSuccessful();
});

it('can list orders for a customer', function (): void {
    $orders = Order::factory()->count(3)->create([
        'customer_id' => $this->customer->id,
        'team_id' => $this->team->id,
    ]);

    livewire(OrdersRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertCanSeeTableRecords($orders);
});

it('can render the invoices relation manager', function (): void {
    livewire(InvoicesRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertSuccessful();
});

it('can list invoices for a customer', function (): void {
    $order = Order::factory()->create([
        'customer_id' => $this->customer->id,
        'team_id' => $this->team->id,
    ]);

    $invoices = Invoice::factory()->count(3)->create([
        'customer_id' => $this->customer->id,
        'order_id' => $order->id,
        'team_id' => $this->team->id,
    ]);

    livewire(InvoicesRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertCanSeeTableRecords($invoices);
});

it('can render the interactions relation manager', function (): void {
    livewire(InteractionsRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertSuccessful();
});

it('can list interactions for a customer', function (): void {
    $interactions = Interaction::factory()->count(3)->create([
        'customer_id' => $this->customer->id,
        'team_id' => $this->team->id,
    ]);

    livewire(InteractionsRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertCanSeeTableRecords($interactions);
});

it('can render the tasks relation manager', function (): void {
    livewire(TasksRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertSuccessful();
});

it('can list tasks for a customer', function (): void {
    $tasks = Task::factory()->count(3)->create([
        'customer_id' => $this->customer->id,
        'team_id' => $this->team->id,
    ]);

    livewire(TasksRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertCanSeeTableRecords($tasks);
});

it('can render the complaints relation manager', function (): void {
    livewire(ComplaintsRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertSuccessful();
});

it('can list complaints for a customer', function (): void {
    $complaints = Complaint::factory()->count(3)->create([
        'customer_id' => $this->customer->id,
        'team_id' => $this->team->id,
    ]);

    livewire(ComplaintsRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertCanSeeTableRecords($complaints);
});

it('can render the communications relation manager', function (): void {
    livewire(CommunicationsRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertSuccessful();
});

it('can list communications for a customer', function (): void {
    $communications = Communication::factory()->count(3)->create([
        'customer_id' => $this->customer->id,
        'team_id' => $this->team->id,
    ]);

    livewire(CommunicationsRelationManager::class, [
        'ownerRecord' => $this->customer,
        'pageClass' => EditCustomer::class,
    ])
        ->assertCanSeeTableRecords($communications);
});
