<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Interaction;
use App\Models\LeadScore;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Team;
use App\Models\User;
use App\Services\LeadScoringService;
use Illuminate\Support\Facades\Notification;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->service = resolve(LeadScoringService::class);
});

it('calculates scores for all active customers in a team', function (): void {
    Customer::factory()->for($this->team)->count(3)->create(['is_active' => true]);
    Customer::factory()->for($this->team)->create(['is_active' => false]);

    $updated = $this->service->calculateForTeam($this->team);

    expect($updated)->toBe(3)
        ->and(LeadScore::query()->count())->toBe(3);
});

it('calculates interaction score capped at 30', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);
    Interaction::factory()->for($this->team)->for($customer)->count(15)->create([
        'interaction_date' => now(),
    ]);

    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    expect($leadScore->interaction_score)->toBe(30);
});

it('calculates recency score based on last interaction date', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);
    Interaction::factory()->for($this->team)->for($customer)->create([
        'interaction_date' => now(),
    ]);

    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    expect($leadScore->recency_score)->toBe(25);
});

it('returns zero recency score when no interactions exist', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);

    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    expect($leadScore->recency_score)->toBe(0);
});

it('calculates opportunity score from count and value', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);
    Opportunity::factory()->for($this->team)->for($customer)->create(['value' => 500_000]);

    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    // 5 points for 1 opportunity + 5 points for 500k value = 10
    expect($leadScore->opportunity_score)->toBe(10);
});

it('calculates engagement score from quotes and orders and profile', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'is_active' => true,
        'email' => 'test@example.com',
        'phone' => '+36201234567',
    ]);
    Quote::factory()->for($this->team)->for($customer)->create();
    Order::factory()->for($this->team)->for($customer)->create();

    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    // 5 (quotes) + 5 (orders) + 5 (complete profile) = 15
    expect($leadScore->engagement_score)->toBe(15);
});

it('updates existing lead score on recalculation', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);

    $this->service->calculateForCustomer($customer, $this->team);
    $this->service->calculateForCustomer($customer, $this->team);

    expect(LeadScore::query()->where('customer_id', $customer->id)->count())->toBe(1);
});

it('assigns leads round robin to team users', function (): void {
    Notification::fake();

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $this->team->users()->attach([$user1->id, $user2->id]);

    $customer1 = Customer::factory()->for($this->team)->create(['is_active' => true]);
    $customer2 = Customer::factory()->for($this->team)->create(['is_active' => true]);

    LeadScore::factory()->for($this->team)->for($customer1)->create([
        'score' => 80,
        'assigned_to' => null,
    ]);
    LeadScore::factory()->for($this->team)->for($customer2)->create([
        'score' => 60,
        'assigned_to' => null,
    ]);

    $assigned = $this->service->assignLeadsRoundRobin($this->team, 50);

    expect($assigned)->toBe(2);

    $assignedUsers = LeadScore::query()
        ->where('team_id', $this->team->id)
        ->whereNotNull('assigned_to')
        ->pluck('assigned_to')
        ->unique()
        ->count();

    expect($assignedUsers)->toBe(2);
});

it('does not assign leads below minimum score', function (): void {
    Notification::fake();

    $user = User::factory()->create();
    $this->team->users()->attach($user);

    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);
    LeadScore::factory()->for($this->team)->for($customer)->create([
        'score' => 30,
        'assigned_to' => null,
    ]);

    $assigned = $this->service->assignLeadsRoundRobin($this->team, 50);

    expect($assigned)->toBe(0);
});

it('returns zero when no team users exist for assignment', function (): void {
    $customer = Customer::factory()->for($this->team)->create(['is_active' => true]);
    LeadScore::factory()->for($this->team)->for($customer)->create([
        'score' => 80,
        'assigned_to' => null,
    ]);

    $assigned = $this->service->assignLeadsRoundRobin($this->team, 50);

    expect($assigned)->toBe(0);
});

it('calculates total score as sum of all components', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'is_active' => true,
        'email' => 'test@example.com',
        'phone' => '+36201234567',
    ]);

    Interaction::factory()->for($this->team)->for($customer)->count(5)->create([
        'interaction_date' => now(),
    ]);
    Opportunity::factory()->for($this->team)->for($customer)->create(['value' => 200_000]);
    Quote::factory()->for($this->team)->for($customer)->create();
    Order::factory()->for($this->team)->for($customer)->create();

    $leadScore = $this->service->calculateForCustomer($customer, $this->team);

    $expectedTotal = $leadScore->interaction_score
        + $leadScore->recency_score
        + $leadScore->opportunity_score
        + $leadScore->engagement_score;

    expect($leadScore->score)->toBe($expectedTotal)
        ->and($leadScore->last_calculated_at)->not->toBeNull();
});
