<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateOrderRequest extends FormRequest
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
            'notes' => ['nullable', 'string'],
            'shipping_address_id' => ['nullable', 'integer', 'exists:customer_addresses,id'],
            'billing_address_id' => ['nullable', 'integer', 'exists:customer_addresses,id'],
        ];
    }
}
