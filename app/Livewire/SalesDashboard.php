<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Concerns\HasCurrentTeam;
use App\Services\SalesDashboardService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class SalesDashboard extends Component
{
    use HasCurrentTeam;

    public string $period = '3';

    /** @var array<string, mixed> */
    public array $kpis = [];

    /** @var array<string, mixed> */
    public array $pipelineData = [];

    /** @var array<string, mixed> */
    public array $trendData = [];

    /** @var array<int, array<string, mixed>> */
    public array $campaignRoiData = [];

    /** @var array<string, mixed> */
    public array $complaintStats = [];

    /** @var array<int, array<string, mixed>> */
    public array $topCustomers = [];

    public function mount(SalesDashboardService $service): void
    {
        $this->loadData($service);
    }

    public function updatedPeriod(SalesDashboardService $service): void
    {
        $this->loadData($service);
    }

    public function render(): View
    {
        return view('livewire.sales-dashboard');
    }

    private function loadData(SalesDashboardService $service): void
    {
        $months = (int) $this->period;

        $this->kpis = $service->getKpis($months);
        $this->pipelineData = $service->getPipelineData();
        $this->trendData = $service->getTrendData($months);
        $this->campaignRoiData = $service->getCampaignRoiData();
        $this->complaintStats = $service->getComplaintStats();
        $this->topCustomers = $service->getTopCustomers();
    }
}
