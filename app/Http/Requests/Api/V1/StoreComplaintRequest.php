<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Enums\ComplaintSeverity;
use App\Enums\ComplaintType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreComplaintRequest extends FormRequest
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
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
            'type' => ['nullable', Rule::enum(ComplaintType::class)],
            'subject' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'severity' => ['required', Rule::enum(ComplaintSeverity::class)],
            'reported_by' => ['nullable', 'integer', 'exists:users,id'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
