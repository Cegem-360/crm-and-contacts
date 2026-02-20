<?php

declare(strict_types=1);

namespace App\Filament\Resources\Customers\Schemas;

use App\Enums\CustomerType;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('unique_identifier')
                    ->default(fn (): string => 'CUST-'.Str::upper(Str::random(8)))
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->required()
                    ->options(CustomerType::class)
                    ->default(CustomerType::Individual),
                TextInput::make('tax_number'),
                TextInput::make('registration_number'),
                TextInput::make('eu_tax_number')
                    ->label('EU Tax Number'),
                TextInput::make('industry'),
                TextInput::make('website')
                    ->url(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                KeyValue::make('custom_fields')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
