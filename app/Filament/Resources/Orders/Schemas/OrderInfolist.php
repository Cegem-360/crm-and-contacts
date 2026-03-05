<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer.name')
                    ->label(__('Customer')),
                TextEntry::make('quote.quote_number')
                    ->label(__('Quote'))
                    ->placeholder(__('-')),
                TextEntry::make('order_number')
                    ->label(__('Order number')),
                TextEntry::make('order_date')
                    ->label(__('Order Date'))
                    ->date(),
                TextEntry::make('status')
                    ->label(__('Status')),
                TextEntry::make('subtotal')
                    ->label(__('Subtotal'))
                    ->numeric(),
                TextEntry::make('discount_amount')
                    ->label(__('Discount amount'))
                    ->numeric(),
                TextEntry::make('tax_amount')
                    ->label(__('Tax amount'))
                    ->numeric(),
                TextEntry::make('total')
                    ->label(__('Total'))
                    ->numeric(),
                TextEntry::make('notes')
                    ->label(__('Notes'))
                    ->placeholder(__('-'))
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->placeholder(__('-')),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->visible(fn (Order $record): bool => $record->trashed()),
            ]);
    }
}
