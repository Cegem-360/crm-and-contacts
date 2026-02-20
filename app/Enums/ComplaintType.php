<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ComplaintType: string implements HasColor, HasLabel
{
    case Product = 'product';
    case Service = 'service';
    case Delivery = 'delivery';
    case Billing = 'billing';
    case Other = 'other';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Product => __('Product'),
            self::Service => __('Service'),
            self::Delivery => __('Delivery'),
            self::Billing => __('Billing'),
            self::Other => __('Other'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Product => 'warning',
            self::Service => 'info',
            self::Delivery => 'primary',
            self::Billing => 'danger',
            self::Other => 'gray',
        };
    }
}
