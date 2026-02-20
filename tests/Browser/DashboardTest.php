<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create([
        'email' => 'dashboard@example.com',
        'password' => Hash::make('password'),
    ]);
    $this->user->teams()->attach($this->team);
});

function loginAndVisitDashboard(object $context): mixed
{
    $page = visit('/admin/login');

    $page->type('#form\\.email', 'dashboard@example.com')
        ->type('#form\\.password', 'password')
        ->submit()
        ->wait(2);

    return visit('/dashboard/'.$context->team->slug);
}

it('renders the dashboard with stats and quick actions', function (): void {
    $page = loginAndVisitDashboard($this);

    $page->assertPathIs('/dashboard/'.$this->team->slug)
        ->assertSee('Welcome')
        ->assertSee($this->user->name)
        ->assertSee('Customers')
        ->assertSee('Opportunities')
        ->assertSee('Open opportunities')
        ->assertSee('Closed opportunities')
        ->assertSee('Quick actions')
        ->assertSee('New opportunity')
        ->assertSee('New customer')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('dashboard/overview'), fullPage: true);
});

it('displays stat cards with seeded data', function (): void {
    $customer = Customer::factory()->for($this->team)->create();
    Opportunity::factory()->for($customer)->for($this->team)->create([
        'stage' => 'lead',
    ]);

    $page = loginAndVisitDashboard($this);

    $page->assertSee('Here is an overview of your sales activities.')
        ->assertSee('View opportunities')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('dashboard/with-data'), fullPage: true);
});
