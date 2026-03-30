<?php

declare(strict_types=1);

use App\Filament\Resources\LoyaltyLevels\Pages\CreateLoyaltyLevel;
use App\Filament\Resources\LoyaltyLevels\Pages\EditLoyaltyLevel;
use App\Filament\Resources\LoyaltyLevels\Pages\ListLoyaltyLevels;
use App\Models\LoyaltyLevel;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Permission;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->user->teams()->attach($this->team);

    Permission::query()->firstOrCreate(['name' => 'view_any_loyalty::level']);
    Permission::query()->firstOrCreate(['name' => 'view_loyalty::level']);
    Permission::query()->firstOrCreate(['name' => 'create_loyalty::level']);
    Permission::query()->firstOrCreate(['name' => 'update_loyalty::level']);
    Permission::query()->firstOrCreate(['name' => 'delete_loyalty::level']);

    $this->user->givePermissionTo([
        'view_any_loyalty::level',
        'view_loyalty::level',
        'create_loyalty::level',
        'update_loyalty::level',
        'delete_loyalty::level',
    ]);

    $this->actingAs($this->user);

    Filament::setTenant($this->team);
    Filament::bootCurrentPanel();
});

it('can render loyalty level list page', function (): void {
    livewire(ListLoyaltyLevels::class)
        ->assertSuccessful();
});

it('can list loyalty levels', function (): void {
    $levels = LoyaltyLevel::factory()->count(3)->create(['team_id' => $this->team->id]);

    livewire(ListLoyaltyLevels::class)
        ->assertCanSeeTableRecords($levels);
});

it('can render create loyalty level page', function (): void {
    livewire(CreateLoyaltyLevel::class)
        ->assertSuccessful();
});

it('can create a loyalty level', function (): void {
    livewire(CreateLoyaltyLevel::class)
        ->fillForm([
            'name' => 'Gold',
            'minimum_points' => 5000,
            'discount_percentage' => 10,
            'sort_order' => 3,
            'is_active' => true,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    $this->assertDatabaseHas(LoyaltyLevel::class, [
        'name' => 'Gold',
        'minimum_points' => 5000,
    ]);
});

it('can render edit loyalty level page', function (): void {
    $level = LoyaltyLevel::factory()->create(['team_id' => $this->team->id]);

    livewire(EditLoyaltyLevel::class, ['record' => $level->id])
        ->assertSuccessful();
});

it('can update a loyalty level', function (): void {
    $level = LoyaltyLevel::factory()->create(['team_id' => $this->team->id]);

    livewire(EditLoyaltyLevel::class, ['record' => $level->id])
        ->fillForm([
            'name' => 'Updated Level',
            'minimum_points' => 9999,
        ])
        ->call('save')
        ->assertNotified();

    $level->refresh();

    expect($level->name)->toBe('Updated Level')
        ->and($level->minimum_points)->toBe(9999);
});

it('validates required fields on create', function (): void {
    livewire(CreateLoyaltyLevel::class)
        ->fillForm([
            'name' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});
