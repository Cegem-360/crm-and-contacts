<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header h2 { margin: 5px 0 0; font-size: 16px; color: #666; }
        .info-section { display: table; width: 100%; margin-bottom: 20px; }
        .info-left, .info-right { display: table-cell; width: 50%; vertical-align: top; }
        .box { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; }
        .label { font-weight: bold; color: #666; font-size: 10px; text-transform: uppercase; margin-bottom: 3px; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.items th { background: #f5f5f5; border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        table.items td { border: 1px solid #ddd; padding: 8px; }
        table.items td.number { text-align: right; }
        .shipment-info { margin-top: 20px; }
        .shipment-info table { width: 100%; }
        .shipment-info td { padding: 5px 0; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>Szállítólevél / Shipping Document</h2>
    </div>

    <div class="info-section">
        <div class="info-left">
            <div class="box">
                <p class="label">Feladó / Sender</p>
                <p style="font-weight: bold;">{{ config('app.name') }}</p>
            </div>

            <div class="box">
                <p class="label">Szállítmányszám / Shipment Number</p>
                <p style="font-size: 16px; font-weight: bold;">{{ $shipment->shipment_number }}</p>

                @if($shipment->order)
                    <p class="label" style="margin-top: 8px;">Rendelés / Order</p>
                    <p>{{ $shipment->order->order_number }}</p>
                @endif

                <p class="label" style="margin-top: 8px;">Fuvarozó / Carrier</p>
                <p>{{ $shipment->carrier }}</p>

                @if($shipment->tracking_number)
                    <p class="label" style="margin-top: 8px;">Nyomkövetési szám / Tracking Number</p>
                    <p style="font-family: monospace; font-size: 14px;">{{ $shipment->tracking_number }}</p>
                @endif
            </div>
        </div>
        <div class="info-right" style="padding-left: 15px;">
            <div class="box">
                <p class="label">Címzett / Recipient</p>
                @if($shipment->customer)
                    <p style="font-weight: bold;">{{ $shipment->customer->name }}</p>
                @endif

                @if($shipment->shipping_address)
                    <p>
                        {{ $shipment->shipping_address['postal_code'] ?? '' }}
                        {{ $shipment->shipping_address['city'] ?? '' }}<br>
                        {{ $shipment->shipping_address['street'] ?? '' }}
                        {{ $shipment->shipping_address['building_number'] ?? '' }}<br>
                        {{ $shipment->shipping_address['country'] ?? '' }}
                    </p>
                @endif
            </div>

            <div class="box">
                <p class="label">Státusz / Status</p>
                <p>{{ $shipment->status->getLabel() }}</p>

                <p class="label" style="margin-top: 8px;">Dátum / Date</p>
                <p>{{ $shipment->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Termék / Product</th>
                <th>SKU</th>
                <th style="text-align: right;">Mennyiség / Quantity</th>
                <th style="text-align: right;">Súly (kg) / Weight</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipment->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->product_sku ?? '-' }}</td>
                    <td class="number">{{ $item->quantity }}</td>
                    <td class="number">{{ $item->weight ? number_format((float)$item->weight, 2) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($shipment->notes)
        <div style="margin-top: 20px; padding: 10px; background: #f9f9f9; border-radius: 4px;">
            <p class="label">Megjegyzés / Notes</p>
            <p>{{ $shipment->notes }}</p>
        </div>
    @endif

    <div class="footer">
        <p>{{ config('app.name') }} - Generálva: {{ now()->format('Y-m-d H:i') }}</p>
    </div>
</body>
</html>
