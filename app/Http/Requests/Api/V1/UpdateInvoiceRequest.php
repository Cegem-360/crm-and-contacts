<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Enums\InvoiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateInvoiceRequest extends FormRequest
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
            'status' => ['nullable', Rule::enum(InvoiceStatus::class)],
            'notes' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
