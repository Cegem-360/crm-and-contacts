<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'quote_id' => ['nullable', 'integer', 'exists:quotes,id'],
            'status' => ['nullable', Rule::enum(OrderStatus::class)],
            'notes' => ['nullable', 'string'],
            'shipping_address_id' => ['nullable', 'integer', 'exists:customer_addresses,id'],
            'billing_address_id' => ['nullable', 'integer', 'exists:customer_addresses,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'integer', 'exists:products,id'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount_amount' => ['nullable', 'numeric', 'min:0'],
            'items.*.tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
