<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum NavigationGroup implements HasIcon, HasLabel
{
    case Campaigns;
    case Sales;
    case Discounts;
    case Customers;
    case Loyalty;
    case Complaints;
    case Products;
    case Reports;
    case System;

    public function getLabel(): string
    {
        return match ($this) {
            self::Campaigns => __('Campaigns'),
            self::Sales => __('Sales'),
            self::Discounts => __('Discounts'),
            self::Customers => __('Customers'),
            self::Loyalty => __('Loyalty Program'),
            self::Complaints => __('Complaints'),
            self::Products => __('Products'),
            self::Reports => __('Reports'),
            self::System => __('System'),
        };
    }

    public function getIcon(): string|Heroicon|null
    {
        return match ($this) {
            self::Campaigns => Heroicon::OutlinedMegaphone,
            self::Sales => Heroicon::OutlinedCurrencyDollar,
            self::Discounts => Heroicon::OutlinedTag,
            self::Customers => Heroicon::OutlinedUsers,
            self::Loyalty => Heroicon::OutlinedStar,
            self::Complaints => Heroicon::OutlinedExclamationTriangle,
            self::Products => Heroicon::OutlinedCubeTransparent,
            self::Reports => Heroicon::OutlinedChartPie,
            self::System => Heroicon::OutlinedCog6Tooth,
        };
    }

    public function getSort(): int
    {
        return match ($this) {
            self::Campaigns => 10,
            self::Sales => 20,
            self::Discounts => 30,
            self::Customers => 40,
            self::Loyalty => 45,
            self::Complaints => 50,
            self::Products => 60,
            self::Reports => 70,
            self::System => 80,
        };
    }
}
