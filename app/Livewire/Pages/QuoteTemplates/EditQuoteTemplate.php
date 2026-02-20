<?php

declare(strict_types=1);

namespace App\Livewire\Pages\QuoteTemplates;

use App\Filament\Resources\QuoteTemplates\Schemas\QuoteTemplateForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\QuoteTemplate;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class EditQuoteTemplate extends Component implements HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithSchemas;

    public ?QuoteTemplate $quoteTemplate = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?QuoteTemplate $quoteTemplate = null): void
    {
        $this->quoteTemplate = $quoteTemplate;
        $this->form->fill($quoteTemplate?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return QuoteTemplateForm::configure($schema)
            ->statePath('data')
            ->model($this->quoteTemplate ?? QuoteTemplate::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->quoteTemplate?->exists) {
            $this->quoteTemplate->update($data);
            $message = __('Quote template updated successfully.');
        } else {
            $this->quoteTemplate = QuoteTemplate::query()->create(array_merge($data, [
                'team_id' => $this->team->id,
                'created_by' => Auth::id(),
            ]));
            $this->form->model($this->quoteTemplate)->saveRelationships();
            $message = __('Quote template created successfully.');
        }

        Notification::make()->title($message)->success()->send();
        $this->redirect(route('dashboard.quote-templates', ['team' => $this->team]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.quote-templates.edit-quote-template');
    }
}
