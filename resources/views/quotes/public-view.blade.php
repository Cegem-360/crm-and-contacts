<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $quote->quote_number }} - Árajánlat / Quote</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; color: #333; }
        .container { max-width: 800px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: #1a1a2e; color: #fff; padding: 30px; text-align: center; }
        .header h1 { font-size: 28px; margin-bottom: 5px; }
        .header p { opacity: 0.8; font-size: 14px; }
        .content { padding: 30px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-block { padding: 15px; background: #f9f9f9; border-radius: 6px; }
        .info-block .label { font-size: 11px; text-transform: uppercase; color: #888; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 5px; }
        .info-block .value { font-size: 16px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead th { background: #f5f5f5; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: #666; border-bottom: 2px solid #ddd; }
        tbody td { padding: 10px 12px; border-bottom: 1px solid #eee; }
        tbody td.number { text-align: right; }
        .totals { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .totals-table { width: 300px; }
        .totals-table td { padding: 6px 10px; }
        .totals-table .total-row { font-weight: bold; font-size: 18px; border-top: 2px solid #333; }
        .notes { padding: 15px; background: #fffde7; border-radius: 6px; border-left: 4px solid #ffc107; margin-bottom: 20px; }
        .notes .label { font-size: 11px; text-transform: uppercase; color: #888; font-weight: 600; margin-bottom: 5px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #999; border-top: 1px solid #eee; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .status-draft { background: #e3f2fd; color: #1565c0; }
        .status-sent { background: #fff3e0; color: #e65100; }
        .status-viewed { background: #f3e5f5; color: #7b1fa2; }
        .status-accepted { background: #e8f5e9; color: #2e7d32; }
        .status-rejected { background: #ffebee; color: #c62828; }
        .status-expired { background: #efebe9; color: #4e342e; }
        @media (max-width: 600px) {
            .info-grid { grid-template-columns: 1fr; }
            .container { margin: 0; border-radius: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Árajánlat / Quote</p>
        </div>

        <div class="content">
            <div class="info-grid">
                <div class="info-block">
                    <div class="label">Ajánlat száma / Quote Number</div>
                    <div class="value">{{ $quote->quote_number }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Státusz / Status</div>
                    <div class="value">
                        <span class="status-badge status-{{ $quote->status->value }}">
                            {{ $quote->status->value }}
                        </span>
                    </div>
                </div>
                <div class="info-block">
                    <div class="label">Kiállítás dátuma / Issue Date</div>
                    <div class="value">{{ $quote->issue_date->format('Y-m-d') }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Érvényesség / Valid Until</div>
                    <div class="value">{{ $quote->valid_until->format('Y-m-d') }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Ügyfél / Customer</div>
                    <div class="value">{{ $quote->customer->name }}</div>
                </div>
                @php
                    $billingAddress = $quote->customer->addresses->where('type', 'billing')->first();
                @endphp
                @if($billingAddress)
                    <div class="info-block">
                        <div class="label">Cím / Address</div>
                        <div class="value" style="font-size: 14px;">
                            {{ $billingAddress->postal_code }} {{ $billingAddress->city }}<br>
                            {{ $billingAddress->street }} {{ $billingAddress->building_number }}
                        </div>
                    </div>
                @endif
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Megnevezés / Description</th>
                        <th style="text-align: right;">Menny. / Qty</th>
                        <th style="text-align: right;">Egységár / Unit Price</th>
                        <th style="text-align: right;">Kedvezmény / Discount</th>
                        <th style="text-align: right;">ÁFA / VAT</th>
                        <th style="text-align: right;">Összesen / Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quote->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->description ?? $item->product?->name ?? '-' }}</td>
                            <td class="number">{{ number_format((float) $item->quantity, 2) }}</td>
                            <td class="number">{{ number_format((float) $item->unit_price, 0, ',', ' ') }} Ft</td>
                            <td class="number">{{ number_format((float) $item->discount_amount, 0, ',', ' ') }} Ft</td>
                            <td class="number">{{ number_format((float) $item->tax_rate, 0) }}%</td>
                            <td class="number">{{ number_format((float) $item->total, 0, ',', ' ') }} Ft</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals">
                <table class="totals-table">
                    <tr>
                        <td>Részösszeg / Subtotal:</td>
                        <td style="text-align: right;">{{ number_format((float) $quote->subtotal, 0, ',', ' ') }} Ft</td>
                    </tr>
                    <tr>
                        <td>Kedvezmény / Discount:</td>
                        <td style="text-align: right;">-{{ number_format((float) $quote->discount_amount, 0, ',', ' ') }} Ft</td>
                    </tr>
                    <tr>
                        <td>ÁFA / VAT:</td>
                        <td style="text-align: right;">{{ number_format((float) $quote->tax_amount, 0, ',', ' ') }} Ft</td>
                    </tr>
                    <tr class="total-row">
                        <td>Összesen / Total:</td>
                        <td style="text-align: right;">{{ number_format((float) $quote->total, 0, ',', ' ') }} Ft</td>
                    </tr>
                </table>
            </div>

            @if($quote->notes)
                <div class="notes">
                    <div class="label">Megjegyzés / Notes</div>
                    <p>{{ $quote->notes }}</p>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>{{ config('app.name') }} &bull; {{ now()->format('Y') }}</p>
        </div>
    </div>
</body>
</html>
