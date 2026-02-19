<?php

declare(strict_types=1);

use App\Enums\OpportunityStage;
use App\Livewire\Dashboard;
use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

it('displays correct stats on the dashboard', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();
    $user->teams()->attach($team);
    $this->actingAs($user);

    app()->instance('current_team', $team);

    $customer = Customer::factory()->create(['team_id' => $team->id]);

    Opportunity::factory()->for($customer)->create(['stage' => OpportunityStage::Lead, 'team_id' => $team->id]);
    Opportunity::factory()->for($customer)->create(['stage' => OpportunityStage::Qualified, 'team_id' => $team->id]);
    Opportunity::factory()->for($customer)->create(['stage' => OpportunityStage::Proposal, 'team_id' => $team->id]);
    Opportunity::factory()->for($customer)->create(['stage' => OpportunityStage::SendedQuotation, 'team_id' => $team->id]);
    Opportunity::factory()->for($customer)->create(['stage' => OpportunityStage::LostQuotation, 'team_id' => $team->id]);

    Livewire::test(Dashboard::class, ['team' => $team])
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
