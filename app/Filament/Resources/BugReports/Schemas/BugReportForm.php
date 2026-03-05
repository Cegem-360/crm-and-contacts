<?php

declare(strict_types=1);

namespace App\Filament\Resources\BugReports\Schemas;

use App\Enums\BugReportStatus;
use App\Enums\ComplaintSeverity;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class BugReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name', modifyQueryUsing: fn ($query) => $query->whereRelation('teams', 'teams.id', resolve('current_team')->getKey())),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('severity')
                    ->options(ComplaintSeverity::class)
                    ->enum(ComplaintSeverity::class)
                    ->required()
                    ->default(ComplaintSeverity::Medium),
                Select::make('status')
                    ->options(BugReportStatus::class)
                    ->enum(BugReportStatus::class)
                    ->required()
                    ->default(BugReportStatus::Open),
                TextInput::make('source')
                    ->nullable(),
                Select::make('assigned_to')
                    ->relationship('assignedUser', 'name', modifyQueryUsing: fn ($query) => $query->whereRelation('teams', 'teams.id', resolve('current_team')->getKey()))
                    ->nullable(),
                DateTimePicker::make('resolved_at'),
            ]);
    }
}
