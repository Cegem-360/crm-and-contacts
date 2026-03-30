<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum LoyaltyTransactionType: string implements HasColor, HasLabel
{
    case Earned = 'earned';
    case Spent = 'spent';
    case Adjusted = 'adjusted';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Earned => __('Earned'),
            self::Spent => __('Spent'),
            self::Adjusted => __('Adjusted'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Earned => 'success',
            self::Spent => 'danger',
            self::Adjusted => 'warning',
        };
    }
}
