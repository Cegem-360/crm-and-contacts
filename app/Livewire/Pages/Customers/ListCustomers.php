<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Customers;

use App\Filament\Exports\CustomerExporter;
use App\Filament\Imports\CustomerImporter;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\ImportAction;
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
final class ListCustomers extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return CustomersTable::configure($table)
            ->query(Customer::query())
            ->recordUrl(fn (Customer $record): string => route('dashboard.customers.view', ['team' => $this->team, 'customer' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (Customer $record): string => route('dashboard.customers.view', ['team' => $this->team, 'customer' => $record]))
                    ->icon(Heroicon::Eye),
                Action::make('edit')
                    ->url(fn (Customer $record): string => route('dashboard.customers.edit', ['team' => $this->team, 'customer' => $record]))
                    ->icon(Heroicon::PencilSquare),
            ])
            ->headerActions([
                ImportAction::make('importCustomers')
                    ->importer(CustomerImporter::class),
                ExportAction::make('exportCustomers')
                    ->exporter(CustomerExporter::class)
                    ->formats([
                        ExportFormat::Xlsx,
                        ExportFormat::Csv,
                    ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.customers.list-customers');
    }
}
