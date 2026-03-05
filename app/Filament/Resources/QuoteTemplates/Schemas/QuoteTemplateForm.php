<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuoteTemplates\Schemas;

use App\Filament\Forms\Components\HtmlCodeEditor;
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
                    ->label(__('Default')),
                Toggle::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
                HtmlCodeEditor::make('body')
                    ->label(__('Blade template'))
                    ->required()
                    ->rows(30)
                    ->columnSpanFull()
                    ->default('')
                    ->helperText(__('Available variables: $quote, $customer, $items. Use standard Blade syntax (@foreach, {{ }}, etc.).')),
            ]);
    }
}
