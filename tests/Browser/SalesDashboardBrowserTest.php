<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create([
        'email' => 'sales@example.com',
        'password' => Hash::make('password'),
    ]);
    $this->user->teams()->attach($this->team);
});

function loginAndVisitSalesDashboard(object $context): mixed
{
    $page = visit('/admin/login');

    $page->type('#form\\.email', 'sales@example.com')
        ->type('#form\\.password', 'password')
        ->submit()
        ->wait(2);

    return visit('/dashboard/'.$context->team->slug.'/sales-dashboard');
}

it('renders the sales dashboard with KPI cards and charts', function (): void {
    $page = loginAndVisitSalesDashboard($this);

    $page->assertPathIs('/dashboard/'.$this->team->slug.'/sales-dashboard')
        ->assertSee('Sales Dashboard')
        ->assertSee('Revenue')
        ->assertSee('Active Quotes')
        ->assertSee('Conversion Rate')
        ->assertSee('Avg Deal Size')
        ->assertSee('Pipeline Overview')
        ->assertSee('Monthly Sales Trend')
        ->assertSee('Campaign ROI')
        ->assertSee('Complaint Statistics')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('sales-dashboard/overview'), fullPage: true);
});
