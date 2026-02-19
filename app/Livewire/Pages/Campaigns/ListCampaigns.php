<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Campaigns;

use App\Filament\Resources\Campaigns\Tables\CampaignsTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Campaign;
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
final class ListCampaigns extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return CampaignsTable::configure($table)
            ->query(Campaign::query())
            ->recordUrl(fn (Campaign $record): string => route('dashboard.campaigns.view', ['team' => $this->team, 'campaign' => $record]))
            ->recordActions([
                Action::make('view')
                    ->url(fn (Campaign $record): string => route('dashboard.campaigns.view', ['team' => $this->team, 'campaign' => $record]))
                    ->icon(Heroicon::Eye),
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.campaigns.list-campaigns');
    }
}
