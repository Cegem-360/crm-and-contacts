<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create([
        'email' => 'customers@example.com',
        'password' => Hash::make('password'),
    ]);
    $this->user->teams()->attach($this->team);
});

function loginAndVisitCustomers(object $context): mixed
{
    $page = visit('/admin/login');

    $page->type('#form\\.email', 'customers@example.com')
        ->type('#form\\.password', 'password')
        ->submit()
        ->wait(2);

    return visit('/dashboard/'.$context->team->slug.'/customers');
}

it('renders the customer list page', function (): void {
    $page = loginAndVisitCustomers($this);

    $page->assertPathIs('/dashboard/'.$this->team->slug.'/customers')
        ->assertSee('Customers')
        ->assertSee('New Customer')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('customers/list'), fullPage: true);
});

it('displays seeded customers in the table', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'Test Customer Corp',
    ]);

    $page = loginAndVisitCustomers($this);

    $page->assertSee('Test Customer Corp')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('customers/with-data'), fullPage: true);
});

it('renders the customer view page', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'View Customer Corp',
    ]);

    loginAndVisitCustomers($this);

    $page = visit('/dashboard/'.$this->team->slug.'/customers/'.$customer->id);

    $page->assertSee('View Customer Corp')
        ->assertSee('Customer details')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('customers/view'), fullPage: true);
});

it('renders the customer edit page', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'Edit Customer Corp',
    ]);

    loginAndVisitCustomers($this);

    $page = visit('/dashboard/'.$this->team->slug.'/customers/'.$customer->id.'/edit');

    $page->assertSee('Edit Customer')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('customers/edit'), fullPage: true);
});
