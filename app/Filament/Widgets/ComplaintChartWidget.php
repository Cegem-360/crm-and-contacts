<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\SalesDashboardService;
use Filament\Widgets\ChartWidget;

final class ComplaintChartWidget extends ChartWidget
{
    protected ?string $heading = null;

    protected static ?int $sort = 6;

    protected ?string $pollingInterval = '30s';

    public function getHeading(): string
    {
        return __('Complaint Statistics');
    }

    protected function getData(): array
    {
        $service = resolve(SalesDashboardService::class);
        $stats = $service->getComplaintStats();
        $byType = $stats['by_type'] ?? [];

        $labels = array_map(
            fn (string $type): string => ucfirst($type),
            array_keys($byType),
        );

        return [
            'datasets' => [
                [
                    'data' => array_values($byType),
                    'backgroundColor' => [
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(156, 163, 175, 0.8)',
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
