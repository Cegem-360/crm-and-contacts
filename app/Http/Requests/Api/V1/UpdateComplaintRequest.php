<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Enums\ComplaintSeverity;
use App\Enums\ComplaintStatus;
use App\Enums\ComplaintType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateComplaintRequest extends FormRequest
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
            'type' => ['nullable', Rule::enum(ComplaintType::class)],
            'subject' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'severity' => ['nullable', Rule::enum(ComplaintSeverity::class)],
            'status' => ['nullable', Rule::enum(ComplaintStatus::class)],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'resolution' => ['nullable', 'string'],
        ];
    }
}
