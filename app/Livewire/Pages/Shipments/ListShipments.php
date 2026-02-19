<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Shipments;

use App\Filament\Resources\Shipments\Tables\ShipmentsTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Shipment;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListShipments extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return ShipmentsTable::configureDashboard(
            $table->query(Shipment::query()->with(['order']))
        );
    }

    public function render(): View
    {
        return view('livewire.pages.shipments.list-shipments');
    }
}
