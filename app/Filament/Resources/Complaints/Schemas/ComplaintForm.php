<?php

declare(strict_types=1);

namespace App\Filament\Resources\Complaints\Schemas;

use App\Enums\ComplaintSeverity;
use App\Enums\ComplaintStatus;
use App\Enums\ComplaintType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('complaint_number')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('order_id')
                    ->relationship('order', 'order_number'),
                Select::make('type')
                    ->options(ComplaintType::class)
                    ->enum(ComplaintType::class),
                TextInput::make('subject'),
                Select::make('reported_by')
                    ->relationship('reporter', 'name')
                    ->nullable(),
                Select::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->nullable(),
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
                    ->options(ComplaintStatus::class)
                    ->enum(ComplaintStatus::class)
                    ->required()
                    ->default(ComplaintStatus::Open),
                TextInput::make('escalation_level')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                Textarea::make('resolution')
                    ->columnSpanFull(),
                DateTimePicker::make('reported_at')
                    ->required(),
                DateTimePicker::make('sla_deadline_at')
                    ->label('SLA Deadline')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit'),
                DateTimePicker::make('resolved_at'),
            ]);
    }
}
