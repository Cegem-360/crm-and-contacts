<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\QuoteTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteTemplate>
 */
final class QuoteTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true).' Template',
            'body' => $this->defaultTemplateBody(),
            'is_default' => false,
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_default' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }

    private function defaultTemplateBody(): string
    {
        return <<<'BLADE'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; }
        .info-section { display: table; width: 100%; margin-bottom: 20px; }
        .info-left, .info-right { display: table-cell; width: 50%; vertical-align: top; }
        .info-right { text-align: right; }
        .label { font-weight: bold; color: #666; font-size: 10px; text-transform: uppercase; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.items th { background: #f5f5f5; border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        table.items td { border: 1px solid #ddd; padding: 8px; }
        table.items td.number { text-align: right; }
        .totals { margin-top: 20px; float: right; width: 300px; }
        .totals table { width: 100%; }
        .totals td { padding: 5px 8px; }
        .totals .total-row { font-weight: bold; font-size: 14px; border-top: 2px solid #333; }
        .notes { margin-top: 40px; padding: 10px; background: #f9f9f9; border-radius: 4px; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Árajánlat / Quote</p>
    </div>
    <div class="info-section">
        <div class="info-left">
            <p class="label">Ajánlat száma / Quote Number</p>
            <p style="font-size: 16px; font-weight: bold;">{{ $quote->quote_number }}</p>
            <p class="label" style="margin-top: 10px;">Kiállítás dátuma / Issue Date</p>
            <p>{{ $quote->issue_date->format('Y-m-d') }}</p>
            <p class="label">Érvényesség / Valid Until</p>
            <p>{{ $quote->valid_until->format('Y-m-d') }}</p>
        </div>
        <div class="info-right">
            <p class="label">Ügyfél / Customer</p>
            <p style="font-weight: bold;">{{ $customer->name }}</p>
        </div>
    </div>
    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Megnevezés / Description</th>
                <th style="text-align: right;">Mennyiség / Qty</th>
                <th style="text-align: right;">Egységár / Unit Price</th>
                <th style="text-align: right;">Kedvezmény / Discount</th>
                <th style="text-align: right;">ÁFA% / VAT%</th>
                <th style="text-align: right;">Összesen / Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->description ?? '-' }}</td>
                    <td class="number">{{ number_format((float)$item->quantity, 2) }}</td>
                    <td class="number">{{ number_format((float)$item->unit_price, 0, ',', ' ') }} Ft</td>
                    <td class="number">{{ number_format((float)$item->discount_amount, 0, ',', ' ') }} Ft</td>
                    <td class="number">{{ number_format((float)$item->tax_rate, 0) }}%</td>
                    <td class="number">{{ number_format((float)$item->total, 0, ',', ' ') }} Ft</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="totals">
        <table>
            <tr>
                <td>Részösszeg / Subtotal:</td>
                <td style="text-align: right;">{{ number_format((float)$quote->subtotal, 0, ',', ' ') }} Ft</td>
            </tr>
            <tr>
                <td>Kedvezmény / Discount:</td>
                <td style="text-align: right;">-{{ number_format((float)$quote->discount_amount, 0, ',', ' ') }} Ft</td>
            </tr>
            <tr>
                <td>ÁFA / VAT:</td>
                <td style="text-align: right;">{{ number_format((float)$quote->tax_amount, 0, ',', ' ') }} Ft</td>
            </tr>
            <tr class="total-row">
                <td>Összesen / Total:</td>
                <td style="text-align: right;">{{ number_format((float)$quote->total, 0, ',', ' ') }} Ft</td>
            </tr>
        </table>
    </div>
    <div style="clear: both;"></div>
    @if($quote->notes)
        <div class="notes">
            <p class="label">Megjegyzés / Notes</p>
            <p>{{ $quote->notes }}</p>
        </div>
    @endif
    <div class="footer">
        <p>{{ config('app.name') }} - Generálva: {{ now()->format('Y-m-d H:i') }}</p>
    </div>
</body>
</html>
BLADE;
    }
}
