<?php

declare(strict_types=1);

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Services\InvoiceService;
use App\Services\OrderService;
use App\Services\ShipmentService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

/** @property Order $record */
final class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('transition_status')
                ->label('Change Status')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->schema(fn (Order $record): array => [
                    Select::make('status')
                        ->label('New Status')
                        ->options(
                            collect($record->status->allowedTransitions())
                                ->mapWithKeys(fn (OrderStatus $status): array => [$status->value => $status->getLabel()])
                                ->toArray()
                        )
                        ->required(),
                ])
                ->action(function (array $data, Order $record, OrderService $orderService): void {
                    $newStatus = OrderStatus::from($data['status']);
                    $orderService->transitionStatus($record, $newStatus);

                    Notification::make()
                        ->success()
                        ->title('Status Updated')
                        ->body(sprintf('Order status changed to %s.', $newStatus->getLabel()))
                        ->send();

                    $this->refreshFormData(['status']);
                })
                ->visible(fn (Order $record): bool => count($record->status->allowedTransitions()) > 0),

            Action::make('generate_invoice')
                ->label('Generate Invoice')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Generate Invoice from Order')
                ->modalDescription('This will create a new invoice with all order items. Are you sure?')
                ->modalSubmitActionLabel('Generate Invoice')
                ->action(function (Order $record, InvoiceService $invoiceService): void {
                    if ($record->invoices()->exists()) {
                        Notification::make()
                            ->warning()
                            ->title('Invoice Already Exists')
                            ->body('An invoice has already been generated for this order.')
                            ->send();

                        return;
                    }

                    $invoice = $invoiceService->createFromOrder($record);

                    Notification::make()
                        ->success()
                        ->title('Invoice Generated')
                        ->body(sprintf('Invoice %s created with %d items.', $invoice->invoice_number, $invoice->invoiceItems->count()))
                        ->send();

                    $this->redirect(route('filament.admin.resources.invoices.edit', ['record' => $invoice->id]));
                })
                ->visible(fn (Order $record): bool => ! $record->invoices()->exists()),

            Action::make('generate_shipment')
                ->label('Create Shipment')
                ->icon('heroicon-o-truck')
                ->color('info')
                ->schema([
                    TextInput::make('carrier')
                        ->label('Carrier')
                        ->required()
                        ->placeholder('e.g. DPD, GLS, FoxPost'),
                ])
                ->action(function (array $data, Order $record, ShipmentService $shipmentService): void {
                    $shipment = $shipmentService->createFromOrder($record, $data['carrier']);

                    Notification::make()
                        ->success()
                        ->title('Shipment Created')
                        ->body(sprintf('Shipment %s created with carrier %s.', $shipment->shipment_number, $shipment->carrier))
                        ->send();

                    $this->redirect(route('filament.admin.resources.shipments.edit', ['record' => $shipment->id]));
                }),

            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
