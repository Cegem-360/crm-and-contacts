<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\SalesDashboardService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class SalesKpiWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $service = resolve(SalesDashboardService::class);
        $kpis = $service->getKpis(1);

        return [
            Stat::make('Monthly Revenue', number_format($kpis['monthly_revenue'], 0).' Ft')
                ->description('Current month')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),
            Stat::make('Active Quotes', (string) $kpis['active_quotes'])
                ->description('Draft quotes')
                ->icon('heroicon-o-document-text')
                ->color('info'),
            Stat::make('Conversion Rate', $kpis['conversion_rate'].'%')
                ->description('Quotes to orders')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('warning'),
            Stat::make('Avg Deal Size', number_format($kpis['avg_deal_size'], 0).' Ft')
                ->description('Average order value')
                ->icon('heroicon-o-banknotes')
                ->color('danger'),
        ];
    }
}
