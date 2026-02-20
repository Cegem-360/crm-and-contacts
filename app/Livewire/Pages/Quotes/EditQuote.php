<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Quotes;

use App\Filament\Resources\Quotes\Schemas\QuoteForm;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Quote;
use App\Models\QuoteTemplate;
use App\Services\QuoteTemplateService;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

#[Layout('components.layouts.dashboard')]
final class EditQuote extends Component implements HasActions, HasSchemas
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?Quote $quote = null;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(?Quote $quote = null): void
    {
        $this->quote = $quote;
        $this->form->fill($quote?->attributesToArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        if ($this->quote?->exists) {
            return QuoteForm::configure($schema)
                ->statePath('data')
                ->model($this->quote);
        }

        return QuoteForm::configureWizard($schema)
            ->statePath('data')
            ->model(Quote::class);
    }

    public function generatePdfAction(): Action
    {
        return Action::make('generatePdf')
            ->label(__('Generate PDF'))
            ->icon('heroicon-o-document-arrow-down')
            ->color('success')
            ->visible(fn (): bool => $this->quote?->exists === true)
            ->schema([
                Select::make('template_id')
                    ->label(__('Template'))
                    ->options(
                        QuoteTemplate::query()
                            ->where('is_active', true)
                            ->pluck('name', 'id'),
                    )
                    ->placeholder(__('Use default template')),
            ])
            ->action(function (array $data): BinaryFileResponse {
                $template = isset($data['template_id'])
                    ? QuoteTemplate::find($data['template_id'])
                    : null;

                $service = App::make(QuoteTemplateService::class);
                $path = $service->generatePdf($this->quote, $template);

                return response()->download($path, $this->quote->quote_number.'.pdf');
            });
    }

    public function sendQuoteAction(): Action
    {
        return Action::make('sendQuote')
            ->label(__('Send Quote'))
            ->icon('heroicon-o-paper-airplane')
            ->color('primary')
            ->visible(fn (): bool => $this->quote?->exists === true)
            ->schema([
                TextInput::make('recipient_email')
                    ->label(__('Email'))
                    ->email()
                    ->required()
                    ->default(fn (): ?string => $this->quote?->customer?->email),
                TextInput::make('recipient_name')
                    ->label(__('Name'))
                    ->required()
                    ->default(fn (): ?string => $this->quote?->customer?->name),
                Select::make('template_id')
                    ->label(__('PDF Template'))
                    ->options(
                        QuoteTemplate::query()
                            ->where('is_active', true)
                            ->pluck('name', 'id'),
                    )
                    ->placeholder(__('Use default template')),
            ])
            ->action(function (array $data): void {
                $template = isset($data['template_id'])
                    ? QuoteTemplate::find($data['template_id'])
                    : null;

                $service = App::make(QuoteTemplateService::class);
                $service->sendQuote(
                    $this->quote,
                    $data['recipient_email'],
                    $data['recipient_name'],
                    $template,
                );

                Notification::make()
                    ->title(__('Quote sent successfully'))
                    ->success()
                    ->send();
            });
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if ($this->quote?->exists) {
            $this->quote->update($data);
            $message = __('Quote updated successfully.');
        } else {
            $this->quote = Quote::query()->create(array_merge($data, ['team_id' => $this->team->id]));
            $this->form->model($this->quote)->saveRelationships();
            $message = __('Quote created successfully.');
        }

        Notification::make()->title($message)->success()->send();
        $this->redirect(route('dashboard.quotes.view', ['team' => $this->team, 'quote' => $this->quote]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.pages.quotes.edit-quote');
    }
}
