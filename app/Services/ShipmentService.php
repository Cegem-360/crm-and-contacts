<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ShipmentStatus;
use App\Models\Order;
use App\Models\Shipment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class ShipmentService
{
    /**
     * @param  array<string, mixed>|null  $shippingAddress
     */
    public function createFromOrder(Order $order, string $carrier, ?array $shippingAddress = null): Shipment
    {
        return DB::transaction(function () use ($order, $carrier, $shippingAddress): Shipment {
            $order->load(['orderItems', 'customer', 'shippingAddress']);

            $address = $shippingAddress;
            if ($address === null && $order->shippingAddress) {
                $addr = $order->shippingAddress;
                $address = [
                    'country' => $addr->country,
                    'postal_code' => $addr->postal_code,
                    'city' => $addr->city,
                    'street' => $addr->street,
                    'building_number' => $addr->building_number,
                ];
            }

            $shipment = Shipment::query()->create([
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'shipment_number' => Shipment::generateShipmentNumber(),
                'carrier' => $carrier,
                'status' => ShipmentStatus::Pending,
                'shipping_address' => $address,
            ]);

            foreach ($order->orderItems as $orderItem) {
                $shipment->items()->create([
                    'order_item_id' => $orderItem->id,
                    'product_name' => $orderItem->description ?? 'Product #'.$orderItem->product_id,
                    'product_sku' => null,
                    'quantity' => (int) $orderItem->quantity,
                ]);
            }

            return $shipment->refresh();
        });
    }

    public function generateShippingDocument(Shipment $shipment): string
    {
        $shipment->load(['customer', 'order', 'items']);

        $pdf = Pdf::loadView('pdf.shipping-document', [
            'shipment' => $shipment,
        ]);

        $filename = 'shipments/'.$shipment->shipment_number.'.pdf';
        Storage::disk('local')->put($filename, $pdf->output());

        $documents = $shipment->documents ?? [];
        $documents[] = $filename;
        $shipment->update(['documents' => $documents]);

        return Storage::disk('local')->path($filename);
    }
}
