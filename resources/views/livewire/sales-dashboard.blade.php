<div>
    {{-- Page header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white font-heading">
                {{ __('Sales Dashboard') }}
            </h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">
                {{ __('Overview of your sales performance and analytics.') }}
            </p>
        </div>
        <div>
            <select wire:model.live="period"
                class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                <option value="1">{{ __('Last month') }}</option>
                <option value="3">{{ __('Last 3 months') }}</option>
                <option value="6">{{ __('Last 6 months') }}</option>
                <option value="12">{{ __('Last 12 months') }}</option>
            </select>
        </div>
    </div>

    {{-- KPI cards --}}
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($kpis['monthly_revenue'] ?? 0, 0) }} Ft</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Revenue') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($kpis['active_quotes'] ?? 0) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Active Quotes') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $kpis['conversion_rate'] ?? 0 }}%</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Conversion Rate') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($kpis['avg_deal_size'] ?? 0, 0) }} Ft</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Avg Deal Size') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts row 1: Pipeline + Trend --}}
    <div class="grid gap-6 lg:grid-cols-2 mb-8">
        {{-- Pipeline Funnel --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Pipeline Overview') }}</h2>
            <div wire:ignore>
                <canvas id="pipelineChart" height="300"></canvas>
            </div>
        </div>

        {{-- Monthly Sales Trend --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Monthly Sales Trend') }}</h2>
            <div wire:ignore>
                <canvas id="trendChart" height="300"></canvas>
            </div>
        </div>
    </div>

    {{-- Charts row 2: Campaign ROI + Complaints --}}
    <div class="grid gap-6 lg:grid-cols-2 mb-8">
        {{-- Campaign ROI --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Campaign ROI') }}</h2>
            @if (count($campaignRoiData) > 0)
                <div wire:ignore>
                    <canvas id="campaignRoiChart" height="300"></canvas>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No campaign data available.') }}</p>
            @endif
        </div>

        {{-- Complaint Statistics --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Complaint Statistics') }}</h2>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $complaintStats['total'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Total') }}</p>
                </div>
                <div class="text-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ $complaintStats['overdue_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Overdue SLA') }}</p>
                </div>
                <div class="text-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 col-span-2">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $complaintStats['avg_resolution_hours'] ?? 0 }}h</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Avg Resolution Time') }}</p>
                </div>
            </div>
            @if (($complaintStats['total'] ?? 0) > 0)
                <div wire:ignore>
                    <canvas id="complaintChart" height="200"></canvas>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('No complaint data available.') }}</p>
            @endif
        </div>
    </div>

    {{-- Top Customers --}}
    @if (count($topCustomers) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Top Customers by Revenue') }}
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <th class="pb-3 font-medium">{{ __('Customer') }}</th>
                            <th class="pb-3 font-medium text-right">{{ __('Orders') }}</th>
                            <th class="pb-3 font-medium text-right">{{ __('Revenue') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($topCustomers as $customer)
                            <tr>
                                <td class="py-3 text-gray-900 dark:text-white font-medium">{{ $customer['name'] }}
                                </td>
                                <td class="py-3 text-right text-gray-600 dark:text-gray-400">
                                    {{ $customer['orders_count'] }}</td>
                                <td class="py-3 text-right text-gray-900 dark:text-white font-medium">
                                    {{ number_format($customer['revenue'], 0) }} Ft</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        const pipelineData = $wire.pipelineData;
        const trendData = $wire.trendData;
        const campaignRoiData = $wire.campaignRoiData;
        const complaintStats = $wire.complaintStats;

        // Pipeline Chart
        if (pipelineData.stages && document.getElementById('pipelineChart')) {
            new Chart(document.getElementById('pipelineChart'), {
                type: 'bar',
                data: {
                    labels: pipelineData.stages.map(s => s.label),
                    datasets: [{
                        label: '{{ __("Value") }}',
                        data: pipelineData.stages.map(s => s.value),
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(249, 115, 22, 0.7)',
                            'rgba(234, 179, 8, 0.7)',
                            'rgba(34, 197, 94, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                        ],
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(156, 163, 175, 0.2)' },
                        },
                        x: {
                            grid: { display: false },
                        }
                    }
                }
            });
        }

        // Trend Chart
        if (trendData.trend && document.getElementById('trendChart')) {
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: trendData.trend.map(t => t.label),
                    datasets: [{
                        label: '{{ __("Orders Revenue") }}',
                        data: trendData.trend.map(t => t.orders_revenue),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4,
                    }, {
                        label: '{{ __("Invoices Total") }}',
                        data: trendData.trend.map(t => t.invoices_total),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(156, 163, 175, 0.2)' },
                        },
                        x: {
                            grid: { display: false },
                        }
                    }
                }
            });
        }

        // Campaign ROI Chart
        if (campaignRoiData.length > 0 && document.getElementById('campaignRoiChart')) {
            new Chart(document.getElementById('campaignRoiChart'), {
                type: 'bar',
                data: {
                    labels: campaignRoiData.map(c => c.name),
                    datasets: [{
                        label: '{{ __("Budget") }}',
                        data: campaignRoiData.map(c => c.budget),
                        backgroundColor: 'rgba(156, 163, 175, 0.7)',
                        borderRadius: 4,
                    }, {
                        label: '{{ __("Revenue") }}',
                        data: campaignRoiData.map(c => c.revenue),
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderRadius: 4,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: 'rgba(156, 163, 175, 0.2)' },
                        },
                        y: {
                            grid: { display: false },
                        }
                    }
                }
            });
        }

        // Complaint Doughnut Chart
        if (complaintStats.by_type && document.getElementById('complaintChart')) {
            const types = Object.keys(complaintStats.by_type);
            const values = Object.values(complaintStats.by_type);
            const colors = [
                'rgba(234, 179, 8, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(156, 163, 175, 0.8)',
            ];

            new Chart(document.getElementById('complaintChart'), {
                type: 'doughnut',
                data: {
                    labels: types.map(t => t.charAt(0).toUpperCase() + t.slice(1)),
                    datasets: [{
                        data: values,
                        backgroundColor: colors.slice(0, types.length),
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                    },
                }
            });
        }
    </script>
@endscript
