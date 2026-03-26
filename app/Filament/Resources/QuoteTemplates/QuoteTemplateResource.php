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
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class QuoteTemplateResource extends Resource
{
    protected static ?string $model = QuoteTemplate::class;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Sales;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?int $navigationSort = 2;

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
