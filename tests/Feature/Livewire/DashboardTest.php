<?php

declare(strict_types=1);

use App\Enums\OpportunityStage;
use App\Livewire\Dashboard;
use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\User;
use Livewire\Livewire;

it('displays correct stats on the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $customer = Customer::factory()->create();

    Opportunity::factory()->for($customer)->create(['stage' => OpportunityStage::Lead]);
    Opportunity::factory()->for($customer)->create(['stage' => OpportunityStage::Qualified]);
    Opportunity::factory()->for($customer)->create(['stage' => OpportunityStage::Proposal]);
    Opportunity::factory()->for($customer)->create(['stage' => OpportunityStage::SendedQuotation]);
    Opportunity::factory()->for($customer)->create(['stage' => OpportunityStage::LostQuotation]);

    Livewire::test(Dashboard::class)
        ->assertSet('totalCustomers', 1)
        ->assertSet('totalOpportunities', 5)
        ->assertSet('openOpportunities', 3)
        ->assertSet('closedOpportunities', 2)
        ->assertSee('Customers')
        ->assertSee('Opportunities')
        ->assertSee('Open opportunities')
        ->assertSee('Closed opportunities')
        ->assertStatus(200);
});

it('requires authentication to view dashboard', function () {
    $this->get('/dashboard')
        ->assertRedirect();
});
