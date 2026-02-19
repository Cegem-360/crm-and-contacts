<?php

declare(strict_types=1);

namespace App\Livewire\Pages\ChatSessions;

use App\Filament\Resources\ChatSessions\Tables\ChatSessionsTable;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\ChatSession;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class ListChatSessions extends Component implements HasActions, HasSchemas, HasTable
{
    use HasCurrentTeam;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return ChatSessionsTable::configure($table)
            ->query(ChatSession::query())
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['customer']));
    }

    public function render(): View
    {
        return view('livewire.pages.chat-sessions.list-chat-sessions');
    }
}
