<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum LoyaltyPointSource: string implements HasLabel
{
    case OrderCompleted = 'order_completed';
    case QuoteAccepted = 'quote_accepted';
    case Referral = 'referral';
    case ManualAdjustment = 'manual_adjustment';
    case LevelBonus = 'level_bonus';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::OrderCompleted => __('Order Completed'),
            self::QuoteAccepted => __('Quote Accepted'),
            self::Referral => __('Referral'),
            self::ManualAdjustment => __('Manual Adjustment'),
            self::LevelBonus => __('Level Bonus'),
        };
    }
}
