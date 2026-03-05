<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadOpportunities\Schemas;

use App\Enums\OpportunityStage;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Slider\Enums\PipsMode;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

final class LeadOpportunityForm
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
                TextInput::make('title')
                    ->label(__('Title'))
                    ->string()
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->columnSpanFull()
                    ->rows(3),
                TextInput::make('value')
                    ->label(__('Value'))
                    ->visible(false)
                    ->numeric()
                    ->prefix('HUF')
                    ->required(),
                Slider::make('probability')
                    ->label(__('Probability'))
                    ->required()
                    ->minValue(0)
                    ->maxValue(100)
                    ->range(minValue: 0, maxValue: 100)
                    ->tooltips()
                    ->step(5)
                    ->default(10)
                    ->fillTrack()
                    ->pips(PipsMode::Steps, 5),
                Select::make('stage')
                    ->label(__('Stage'))
                    ->options(OpportunityStage::class)
                    ->default(OpportunityStage::Lead)
                    ->required(),
                DatePicker::make('expected_close_date')
                    ->label(__('Expected close date'))
                    ->native(false)
                    ->required(),
                Select::make('assigned_to')
                    ->label(__('Assigned User'))
                    ->relationship('assignedUser', 'name', modifyQueryUsing: fn ($query) => $query->whereRelation('teams', 'teams.id', resolve('current_team')->getKey()))
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->default(Auth::id())
                    ->required(),
            ]);
    }
}
