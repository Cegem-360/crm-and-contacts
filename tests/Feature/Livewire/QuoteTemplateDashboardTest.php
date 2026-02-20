<?php

declare(strict_types=1);

use App\Livewire\Pages\QuoteTemplates\EditQuoteTemplate;
use App\Livewire\Pages\QuoteTemplates\ListQuoteTemplates;
use App\Models\QuoteTemplate;
use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create();
    $this->user->teams()->attach($this->team);
    $this->actingAs($this->user);

    app()->instance('current_team', $this->team);
});

it('renders the quote templates list page', function (): void {
    Livewire::test(ListQuoteTemplates::class, ['team' => $this->team])
        ->assertStatus(200)
        ->assertSee('Quote Templates');
});

it('displays quote templates in the table', function (): void {
    $template = QuoteTemplate::factory()->for($this->team)->create([
        'name' => 'Feature Test Template',
        'created_by' => $this->user->id,
    ]);

    Livewire::test(ListQuoteTemplates::class, ['team' => $this->team])
        ->assertSee('Feature Test Template');
});

it('renders the edit page for an existing template', function (): void {
    $template = QuoteTemplate::factory()->for($this->team)->create([
        'name' => 'Edit Me Template',
        'created_by' => $this->user->id,
    ]);

    Livewire::test(EditQuoteTemplate::class, ['team' => $this->team, 'quoteTemplate' => $template])
        ->assertStatus(200)
        ->assertSee('Edit Quote Template');
});

it('renders the create page for a new template', function (): void {
    Livewire::test(EditQuoteTemplate::class, ['team' => $this->team])
        ->assertStatus(200)
        ->assertSee('New Quote Template');
});

it('creates a new quote template', function (): void {
    Livewire::test(EditQuoteTemplate::class, ['team' => $this->team])
        ->fillForm([
            'name' => 'New Test Template',
            'body' => '<p>Template body</p>',
            'is_active' => true,
            'is_default' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertRedirect(route('dashboard.quote-templates', ['team' => $this->team]));

    $this->assertDatabaseHas('quote_templates', [
        'name' => 'New Test Template',
        'team_id' => $this->team->id,
        'created_by' => $this->user->id,
    ]);
});

it('updates an existing quote template', function (): void {
    $template = QuoteTemplate::factory()->for($this->team)->create([
        'name' => 'Old Name',
        'created_by' => $this->user->id,
    ]);

    Livewire::test(EditQuoteTemplate::class, ['team' => $this->team, 'quoteTemplate' => $template])
        ->fillForm([
            'name' => 'Updated Name',
            'body' => $template->body,
            'is_active' => true,
            'is_default' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertRedirect(route('dashboard.quote-templates', ['team' => $this->team]));

    $this->assertDatabaseHas('quote_templates', [
        'id' => $template->id,
        'name' => 'Updated Name',
    ]);
});

it('requires authentication to view quote templates', function (): void {
    auth()->logout();

    $this->get('/dashboard/'.$this->team->slug.'/quote-templates')
        ->assertRedirect();
});
