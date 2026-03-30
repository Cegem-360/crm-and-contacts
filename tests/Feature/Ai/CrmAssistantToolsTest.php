<?php

declare(strict_types=1);

use App\Ai\Tools\AggregateModel;
use App\Ai\Tools\GetModelDetails;
use App\Ai\Tools\ListModels;
use App\Ai\Tools\QueryModel;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Laravel\Ai\Tools\Request;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->user->teams()->attach($this->team);

    $this->actingAs($this->user);
    Filament::setTenant($this->team);
});

// ListModels tool tests

it('lists all available models', function (): void {
    $tool = new ListModels();
    $result = json_decode($tool->handle(new Request([])), true);

    expect($result)->toBeArray()
        ->and(collect($result)->pluck('key')->toArray())->toContain('customers', 'orders', 'products', 'invoices', 'opportunities');
});

it('returns model descriptions and relationships', function (): void {
    $tool = new ListModels();
    $result = json_decode($tool->handle(new Request([])), true);

    $customers = collect($result)->firstWhere('key', 'customers');

    expect($customers)
        ->toHaveKey('description')
        ->toHaveKey('available_relationships')
        ->and($customers['available_relationships'])->toContain('orders', 'invoices', 'contacts');
});

// QueryModel tool tests

it('queries customers', function (): void {
    Customer::factory()->count(3)->create(['team_id' => $this->team->id]);

    $tool = new QueryModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'limit' => 10,
    ])), true);

    expect($result['model'])->toBe('customers')
        ->and($result['count'])->toBe(3)
        ->and($result['data'])->toHaveCount(3);
});

it('filters query results', function (): void {
    Customer::factory()->create(['team_id' => $this->team->id, 'is_active' => true]);
    Customer::factory()->create(['team_id' => $this->team->id, 'is_active' => false]);

    $tool = new QueryModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'filters' => ['is_active' => true],
    ])), true);

    expect($result['count'])->toBe(1)
        ->and($result['data'][0]['is_active'])->toBeTrue();
});

it('searches customers by name', function (): void {
    Customer::factory()->create(['team_id' => $this->team->id, 'name' => 'Acme Corporation']);
    Customer::factory()->create(['team_id' => $this->team->id, 'name' => 'Beta Industries']);

    $tool = new QueryModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'search' => 'Acme',
    ])), true);

    expect($result['count'])->toBe(1)
        ->and($result['data'][0]['name'])->toBe('Acme Corporation');
});

it('limits results to max 50', function (): void {
    $tool = new QueryModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'limit' => 999,
    ])), true);

    // Should not error; limit is capped internally
    expect($result['model'])->toBe('customers');
});

it('returns error for unknown model', function (): void {
    $tool = new QueryModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'nonexistent',
    ])), true);

    expect($result)->toHaveKey('error')
        ->and($result['error'])->toContain('Unknown model');
});

it('eager loads relationships', function (): void {
    $customer = Customer::factory()->create(['team_id' => $this->team->id]);
    Order::factory()->create(['team_id' => $this->team->id, 'customer_id' => $customer->id]);

    $tool = new QueryModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'with' => ['orders'],
    ])), true);

    expect($result['data'][0])->toHaveKey('orders')
        ->and($result['data'][0]['orders'])->toHaveCount(1);
});

// GetModelDetails tool tests

it('gets a specific record by id', function (): void {
    $customer = Customer::factory()->create(['team_id' => $this->team->id]);

    $tool = new GetModelDetails();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'id' => $customer->id,
    ])), true);

    expect($result['data']['id'])->toBe($customer->id)
        ->and($result['data']['name'])->toBe($customer->name);
});

it('returns error for non-existent record', function (): void {
    $tool = new GetModelDetails();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'id' => 99999,
    ])), true);

    expect($result)->toHaveKey('error')
        ->and($result['error'])->toContain('not found');
});

it('loads relationships on detail view', function (): void {
    $customer = Customer::factory()->create(['team_id' => $this->team->id]);
    Order::factory()->count(2)->create(['team_id' => $this->team->id, 'customer_id' => $customer->id]);

    $tool = new GetModelDetails();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'id' => $customer->id,
        'with' => ['orders'],
    ])), true);

    expect($result['data']['orders'])->toHaveCount(2);
});

// AggregateModel tool tests

it('counts records', function (): void {
    Customer::factory()->count(5)->create(['team_id' => $this->team->id]);

    $tool = new AggregateModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'operation' => 'count',
    ])), true);

    expect($result['result'])->toBe(5);
});

it('sums a column', function (): void {
    Product::factory()->create(['team_id' => $this->team->id, 'unit_price' => 100]);
    Product::factory()->create(['team_id' => $this->team->id, 'unit_price' => 200]);

    $tool = new AggregateModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'products',
        'operation' => 'sum',
        'column' => 'unit_price',
    ])), true);

    expect((float) $result['result'])->toBe(300.0);
});

it('calculates averages', function (): void {
    Product::factory()->create(['team_id' => $this->team->id, 'unit_price' => 100]);
    Product::factory()->create(['team_id' => $this->team->id, 'unit_price' => 200]);

    $tool = new AggregateModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'products',
        'operation' => 'avg',
        'column' => 'unit_price',
    ])), true);

    expect((float) $result['result'])->toBe(150.0);
});

it('filters aggregates', function (): void {
    Customer::factory()->count(3)->create(['team_id' => $this->team->id, 'is_active' => true]);
    Customer::factory()->count(2)->create(['team_id' => $this->team->id, 'is_active' => false]);

    $tool = new AggregateModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'operation' => 'count',
        'filters' => ['is_active' => true],
    ])), true);

    expect($result['result'])->toBe(3);
});

it('rejects invalid aggregate operations', function (): void {
    $tool = new AggregateModel();
    $result = json_decode($tool->handle(new Request([
        'model' => 'customers',
        'operation' => 'delete',
    ])), true);

    expect($result)->toHaveKey('error')
        ->and($result['error'])->toContain('Invalid operation');
});
