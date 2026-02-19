<?php

declare(strict_types=1);

namespace App\Filament\Resources\LeadOpportunities\Tables;

use App\Enums\OpportunityStage;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class LeadOpportunitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->where('stage', OpportunityStage::Lead))
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.email')
                    ->label('Customer Email')
                    ->searchable(),
                TextColumn::make('customer.phone')
                    ->label('Customer Phone')
                    ->searchable(),
                TextColumn::make('customer.type')
                    ->label('Customer Type')
                    ->badge(),
                TextColumn::make('title')
                    ->label('Opportunity Title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('probability')
                    ->label('Probability')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('expected_close_date')
                    ->label('Expected Close Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('assignedUser.name')
                    ->label('Assigned To')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }

    public static function configureDashboard(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('probability')
                    ->label(__('Probability'))
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('expected_close_date')
                    ->label(__('Expected Close'))
                    ->date()
                    ->sortable(),
                TextColumn::make('assignedUser.name')
                    ->label(__('Assigned To'))
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->url(fn ($record): string => route('dashboard.opportunities.edit', ['team' => app('current_team'), 'opportunity' => $record])),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}
