<?php

declare(strict_types=1);

use App\Enums\OpportunityStage;
use App\Livewire\KanbanBoard;
use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create();
    $this->user->teams()->attach($this->team);
    $this->actingAs($this->user);
    app()->instance('current_team', $this->team);
    $this->customer = Customer::factory()->for($this->team)->create();
});

it('renders the kanban board', function (): void {
    Livewire::test(KanbanBoard::class, ['team' => $this->team])
        ->assertOk()
        ->assertSee(__('Pipeline Kanban'));
});

it('displays opportunities in their correct stages', function (): void {
    $leadOpp = Opportunity::factory()->for($this->customer)->for($this->team)->create([
        'stage' => OpportunityStage::Lead,
        'title' => 'Test Lead Opp',
        'value' => 50000,
    ]);

    $proposalOpp = Opportunity::factory()->for($this->customer)->for($this->team)->create([
        'stage' => OpportunityStage::Proposal,
        'title' => 'Test Proposal Opp',
        'value' => 100000,
    ]);

    $component = Livewire::test(KanbanBoard::class, ['team' => $this->team]);

    $stages = $component->get('stages');

    expect($stages['lead']['count'])->toBe(1)
        ->and($stages['lead']['total'])->toBe(50000.0)
        ->and($stages['proposal']['count'])->toBe(1)
        ->and($stages['proposal']['total'])->toBe(100000.0);
});

it('moves opportunity between stages', function (): void {
    $opp = Opportunity::factory()->for($this->customer)->for($this->team)->create([
        'stage' => OpportunityStage::Lead,
        'title' => 'Moving Opp',
        'value' => 75000,
    ]);

    Livewire::test(KanbanBoard::class, ['team' => $this->team])
        ->call('moveOpportunity', $opp->id, 'qualified');

    expect($opp->fresh()->stage)->toBe(OpportunityStage::Qualified);
});

it('filters by assignee', function (): void {
    $otherUser = User::factory()->create();
    $otherUser->teams()->attach($this->team);

    Opportunity::factory()->for($this->customer)->for($this->team)->create([
        'stage' => OpportunityStage::Lead,
        'assigned_to' => $this->user->id,
    ]);

    Opportunity::factory()->for($this->customer)->for($this->team)->create([
        'stage' => OpportunityStage::Lead,
        'assigned_to' => $otherUser->id,
    ]);

    $component = Livewire::test(KanbanBoard::class, ['team' => $this->team])
        ->set('filterAssignee', (string) $this->user->id);

    $stages = $component->get('stages');
    expect($stages['lead']['count'])->toBe(1);
});

it('filters by value range', function (): void {
    Opportunity::factory()->for($this->customer)->for($this->team)->create([
        'stage' => OpportunityStage::Lead,
        'value' => 10000,
    ]);

    Opportunity::factory()->for($this->customer)->for($this->team)->create([
        'stage' => OpportunityStage::Lead,
        'value' => 200000,
    ]);

    $component = Livewire::test(KanbanBoard::class, ['team' => $this->team])
        ->set('filterMinValue', '50000');

    $stages = $component->get('stages');
    expect($stages['lead']['count'])->toBe(1)
        ->and($stages['lead']['total'])->toBe(200000.0);
});

it('loads team users for filter dropdown', function (): void {
    $component = Livewire::test(KanbanBoard::class, ['team' => $this->team]);

    $teamUsers = $component->get('teamUsers');
    expect($teamUsers)->toHaveCount(1)
        ->and($teamUsers[0]['name'])->toBe($this->user->name);
});

it('shows all stages from OpportunityStage enum', function (): void {
    $component = Livewire::test(KanbanBoard::class, ['team' => $this->team]);

    $stages = $component->get('stages');
    $expectedStages = array_map(fn ($s) => $s->value, OpportunityStage::cases());

    expect(array_keys($stages))->toBe($expectedStages);
});
