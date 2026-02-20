<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\SalesDashboardService;
use Filament\Widgets\ChartWidget;

final class PipelineChartWidget extends ChartWidget
{
    protected ?string $heading = 'Pipeline Overview';

    protected static ?int $sort = 2;

    protected ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $service = resolve(SalesDashboardService::class);
        $pipeline = $service->getPipelineData();

        $stages = $pipeline['stages'] ?? [];

        return [
            'datasets' => [
                [
                    'label' => 'Pipeline Value',
                    'data' => array_map(fn (array $stage): float => $stage['value'], $stages),
                    'backgroundColor' => [
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(249, 115, 22, 0.7)',
                        'rgba(234, 179, 8, 0.7)',
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                    ],
                    'borderRadius' => 6,
                ],
            ],
            'labels' => array_map(fn (array $stage): string => $stage['label'], $stages),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
