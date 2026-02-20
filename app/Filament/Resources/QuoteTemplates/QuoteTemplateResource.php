<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuoteTemplates;

use App\Enums\NavigationGroup;
use App\Filament\Resources\QuoteTemplates\Pages\CreateQuoteTemplate;
use App\Filament\Resources\QuoteTemplates\Pages\EditQuoteTemplate;
use App\Filament\Resources\QuoteTemplates\Pages\ListQuoteTemplates;
use App\Filament\Resources\QuoteTemplates\Schemas\QuoteTemplateForm;
use App\Filament\Resources\QuoteTemplates\Tables\QuoteTemplatesTable;
use App\Models\QuoteTemplate;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

final class QuoteTemplateResource extends Resource
{
    protected static ?string $model = QuoteTemplate::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Settings;

    public static function form(Schema $schema): Schema
    {
        return QuoteTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuoteTemplatesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuoteTemplates::route('/'),
            'create' => CreateQuoteTemplate::route('/create'),
            'edit' => EditQuoteTemplate::route('/{record}/edit'),
        ];
    }
}
