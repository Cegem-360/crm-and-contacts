<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum PriceRuleType: string implements HasLabel
{
    case Quantity = 'quantity';
    case ValueThreshold = 'value_threshold';
    case CustomerSpecific = 'customer_specific';
    case Seasonal = 'seasonal';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Quantity => __('Quantity'),
            self::ValueThreshold => __('Value Threshold'),
            self::CustomerSpecific => __('Customer Specific'),
            self::Seasonal => __('Seasonal'),
        };
    }
}
