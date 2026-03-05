<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

final class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label(__('Customer'))
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('assigned_to')
                    ->label(__('Assigned To'))
                    ->relationship('assignedUser', 'name', modifyQueryUsing: fn ($query) => $query->whereRelation('teams', 'teams.id', resolve('current_team')->getKey()))
                    ->searchable()
                    ->preload()
                    ->default(Auth::id())
                    ->required(),
                Select::make('assigned_by')
                    ->label(__('Assigned By'))
                    ->relationship('assigner', 'name', modifyQueryUsing: fn ($query) => $query->whereRelation('teams', 'teams.id', resolve('current_team')->getKey()))
                    ->searchable()
                    ->preload()
                    ->default(Auth::id())
                    ->required(),
                TextInput::make('title')
                    ->label(__('Title'))
                    ->required(),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->columnSpanFull(),
                Select::make('priority')
                    ->label(__('Priority'))
                    ->options([
                        'low' => __('Low'),
                        'medium' => __('Medium'),
                        'high' => __('High'),
                        'urgent' => __('Critical'),
                    ])
                    ->required()
                    ->default('medium'),
                Select::make('status')
                    ->label(__('Status'))
                    ->options([
                        'pending' => __('Pending'),
                        'in_progress' => __('In Progress'),
                        'completed' => __('Completed'),
                        'cancelled' => __('Cancelled'),
                    ])
                    ->required()
                    ->default('pending'),
                DatePicker::make('due_date')
                    ->label(__('Due Date')),
                DateTimePicker::make('completed_at')
                    ->label(__('Completed at')),
            ]);
    }
}
