<?php

declare(strict_types=1);

namespace App\Livewire\Pages\QuoteTemplates;

use App\Filament\Resources\QuoteTemplates\Tables\QuoteTemplatesTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\QuoteTemplate;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListQuoteTemplates extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return QuoteTemplatesTable::configure($table)
            ->query(QuoteTemplate::query())
            ->recordActions([
                Action::make('edit')
                    ->url(fn (QuoteTemplate $record): string => route('dashboard.quote-templates.edit', ['team' => $this->team, 'quoteTemplate' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.quote-templates.list-quote-templates');
    }
}
