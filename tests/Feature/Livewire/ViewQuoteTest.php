<?php

declare(strict_types=1);

use App\Livewire\Pages\Quotes\ViewQuote;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\QuoteItem;
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

    $this->customer = Customer::factory()->for($this->team)->create();
    $this->quote = Quote::factory()->draft()->create([
        'customer_id' => $this->customer->id,
        'team_id' => $this->team->id,
    ]);
    QuoteItem::factory()->create([
        'quote_id' => $this->quote->id,
        'team_id' => $this->team->id,
    ]);
});

it('shows error notification when generating PDF without template and no default exists', function (): void {
    Livewire::test(ViewQuote::class, ['team' => $this->team, 'quote' => $this->quote])
        ->callAction('generatePdf', ['template_id' => null])
        ->assertNotified(__('No template available'));
});

it('shows error notification when sending quote without template and no default exists', function (): void {
    Livewire::test(ViewQuote::class, ['team' => $this->team, 'quote' => $this->quote])
        ->callAction('sendQuote', [
            'recipient_email' => 'test@example.com',
            'recipient_name' => 'Test User',
            'template_id' => null,
        ])
        ->assertNotified(__('No template available'));
});

it('generates PDF successfully with a selected template', function (): void {
    $template = QuoteTemplate::factory()->for($this->team)->create();

    Livewire::test(ViewQuote::class, ['team' => $this->team, 'quote' => $this->quote])
        ->callAction('generatePdf', ['template_id' => $template->id])
        ->assertHasNoActionErrors();
});

it('generates PDF successfully with default template', function (): void {
    QuoteTemplate::factory()->default()->for($this->team)->create();

    Livewire::test(ViewQuote::class, ['team' => $this->team, 'quote' => $this->quote])
        ->callAction('generatePdf', ['template_id' => null])
        ->assertHasNoActionErrors();
});
