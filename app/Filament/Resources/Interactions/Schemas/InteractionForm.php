<?php

declare(strict_types=1);

namespace App\Filament\Resources\Interactions\Schemas;

use App\Enums\InteractionType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class InteractionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label(__('Customer'))
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('user_id')
                    ->label(__('User'))
                    ->relationship('user', 'name', modifyQueryUsing: fn ($query) => $query->whereRelation('teams', 'teams.id', resolve('current_team')->getKey()))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->label(__('Type'))
                    ->options(InteractionType::class)
                    ->required()
                    ->default('note'),
                TextInput::make('subject')
                    ->label(__('Subject'))
                    ->required(),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->columnSpanFull(),
                DateTimePicker::make('interaction_date')
                    ->label(__('Interaction date'))
                    ->required(),
                TextInput::make('duration')
                    ->label(__('Duration'))
                    ->numeric(),
                TextInput::make('next_action')
                    ->label(__('Next action')),
                DatePicker::make('next_action_date')
                    ->label(__('Next action date')),
            ]);
    }
}
