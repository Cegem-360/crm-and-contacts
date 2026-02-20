<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuoteTemplates\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class QuoteTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_default')
                    ->label('Default template'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Textarea::make('body')
                    ->label('Blade template')
                    ->required()
                    ->rows(30)
                    ->columnSpanFull()
                    ->helperText('Available variables: $quote, $customer, $items. Use standard Blade syntax (@foreach, {{ }}, etc.)'),
            ]);
    }
}
