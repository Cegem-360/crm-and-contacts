<?php

declare(strict_types=1);

use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\RelationManagers\BugReportsRelationManager;
use App\Filament\Resources\Users\RelationManagers\ChatSessionsRelationManager;
use App\Filament\Resources\Users\RelationManagers\InteractionsRelationManager;
use App\Models\BugReport;
use App\Models\ChatSession;
use App\Models\Interaction;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->user->teams()->attach($this->team);

    $this->actingAs($this->user);

    Filament::setTenant($this->team);
    Filament::bootCurrentPanel();
});

it('can render the bug reports relation manager', function (): void {
    livewire(BugReportsRelationManager::class, [
        'ownerRecord' => $this->user,
        'pageClass' => EditUser::class,
    ])
        ->assertSuccessful();
});

it('can list bug reports for a user', function (): void {
    $bugReports = BugReport::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    livewire(BugReportsRelationManager::class, [
        'ownerRecord' => $this->user,
        'pageClass' => EditUser::class,
    ])
        ->assertCanSeeTableRecords($bugReports);
});

it('can render the chat sessions relation manager', function (): void {
    livewire(ChatSessionsRelationManager::class, [
        'ownerRecord' => $this->user,
        'pageClass' => EditUser::class,
    ])
        ->assertSuccessful();
});

it('can list chat sessions for a user', function (): void {
    $chatSessions = ChatSession::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    livewire(ChatSessionsRelationManager::class, [
        'ownerRecord' => $this->user,
        'pageClass' => EditUser::class,
    ])
        ->assertCanSeeTableRecords($chatSessions);
});

it('can render the interactions relation manager', function (): void {
    livewire(InteractionsRelationManager::class, [
        'ownerRecord' => $this->user,
        'pageClass' => EditUser::class,
    ])
        ->assertSuccessful();
});

it('can list interactions for a user', function (): void {
    $interactions = Interaction::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    livewire(InteractionsRelationManager::class, [
        'ownerRecord' => $this->user,
        'pageClass' => EditUser::class,
    ])
        ->assertCanSeeTableRecords($interactions);
});
