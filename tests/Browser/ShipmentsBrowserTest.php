<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create([
        'email' => 'shipments@example.com',
        'password' => Hash::make('password'),
    ]);
    $this->user->teams()->attach($this->team);
});

function loginAndVisitShipments(object $context): mixed
{
    $page = visit('/admin/login');

    $page->type('#form\\.email', 'shipments@example.com')
        ->type('#form\\.password', 'password')
        ->submit()
        ->wait(2);

    return visit('/dashboard/'.$context->team->slug.'/shipments');
}

it('renders the shipments list page', function (): void {
    $page = loginAndVisitShipments($this);

    $page->assertPathIs('/dashboard/'.$this->team->slug.'/shipments')
        ->assertSee('Shipments')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('shipments/list'), fullPage: true);
});

it('displays seeded shipments in the table', function (): void {
    $customer = Customer::factory()->for($this->team)->create();
    $order = Order::factory()->for($customer)->for($this->team)->create([
        'order_number' => 'ORD-SHIP-001',
    ]);

    Shipment::factory()->for($customer)->for($order)->for($this->team)->pending()->create([
        'shipment_number' => 'SHP-TEST-001',
    ]);

    $page = loginAndVisitShipments($this);

    $page->assertSee('SHP-TEST-001')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('shipments/with-data'), fullPage: true);
});
