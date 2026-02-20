<?php

declare(strict_types=1);

use App\Filament\Resources\QuoteTemplates\Pages\CreateQuoteTemplate;
use App\Filament\Resources\QuoteTemplates\Pages\EditQuoteTemplate;
use App\Filament\Resources\QuoteTemplates\Pages\ListQuoteTemplates;
use App\Models\QuoteTemplate;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Permission;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->user->teams()->attach($this->team);

    Permission::query()->firstOrCreate(['name' => 'view_any_quote_template']);
    Permission::query()->firstOrCreate(['name' => 'view_quote_template']);
    Permission::query()->firstOrCreate(['name' => 'create_quote_template']);
    Permission::query()->firstOrCreate(['name' => 'update_quote_template']);
    Permission::query()->firstOrCreate(['name' => 'delete_quote_template']);

    $this->user->givePermissionTo([
        'view_any_quote_template',
        'view_quote_template',
        'create_quote_template',
        'update_quote_template',
        'delete_quote_template',
    ]);

    $this->actingAs($this->user);

    Filament::setTenant($this->team);
    Filament::bootCurrentPanel();
});

it('can render quote template list page', function (): void {
    livewire(ListQuoteTemplates::class)
        ->assertSuccessful();
});

it('can list quote templates', function (): void {
    $templates = QuoteTemplate::factory()->count(3)->create(['team_id' => $this->team->id]);

    livewire(ListQuoteTemplates::class)
        ->assertCanSeeTableRecords($templates);
});

it('can render create quote template page', function (): void {
    livewire(CreateQuoteTemplate::class)
        ->assertSuccessful();
});

it('can create a quote template', function (): void {
    livewire(CreateQuoteTemplate::class)
        ->fillForm([
            'name' => 'Test Template',
            'body' => '<html><body>{{ $quote->quote_number }}</body></html>',
            'is_default' => true,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(QuoteTemplate::class, [
        'name' => 'Test Template',
        'is_default' => true,
    ]);
});

it('can render edit quote template page', function (): void {
    $template = QuoteTemplate::factory()->create(['team_id' => $this->team->id]);

    livewire(EditQuoteTemplate::class, ['record' => $template->id])
        ->assertSuccessful();
});

it('validates required fields on create', function (): void {
    livewire(CreateQuoteTemplate::class)
        ->fillForm([
            'name' => null,
            'body' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'body' => 'required',
        ]);
});

it('cannot access list page without permission', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($this->team);
    $this->actingAs($user);

    livewire(ListQuoteTemplates::class)
        ->assertForbidden();
});
