<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Schemas;

use App\Enums\CustomerType;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('unique_identifier')
                    ->label(__('Unique identifier'))
                    ->default(fn (): string => 'CUST-'.Str::upper(Str::random(8)))
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                Select::make('type')
                    ->label(__('Type'))
                    ->required()
                    ->options(CustomerType::class)
                    ->default(CustomerType::Individual),
                TextInput::make('tax_number')
                    ->label(__('Tax number')),
                TextInput::make('registration_number')
                    ->label(__('Registration number')),
                TextInput::make('eu_tax_number')
                    ->label(__('EU Tax Number')),
                TextInput::make('industry')
                    ->label(__('Industry')),
                TextInput::make('website')
                    ->label(__('Website'))
                    ->url(),
                TextInput::make('email')
                    ->label(__('Email address'))
                    ->email(),
                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->tel(),
                Textarea::make('notes')
                    ->label(__('Notes'))
                    ->columnSpanFull(),
                KeyValue::make('custom_fields')
                    ->label(__('Custom Fields'))
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label(__('Is active'))
                    ->required(),
                Section::make(__('Loyalty Program'))
                    ->columns(3)
                    ->visible(fn (?object $record): bool => $record !== null)
                    ->components([
                        Placeholder::make('loyalty_points_display')
                            ->label(__('Current Points'))
                            ->content(fn (?object $record): string => (string) ($record?->loyalty_points ?? 0)),
                        Placeholder::make('loyalty_level_display')
                            ->label(__('Current Level'))
                            ->content(fn (?object $record): string => $record?->loyaltyLevel?->name ?? __('None')),
                        Placeholder::make('loyalty_discount_display')
                            ->label(__('Discount Percentage'))
                            ->content(fn (?object $record): string => $record?->loyaltyLevel
                                ? $record->loyaltyLevel->discount_percentage.'%'
                                : '0%'),
                    ]),
            ]);
    }
}
