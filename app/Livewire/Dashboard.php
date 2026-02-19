<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\OpportunityStage;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Customer;
use App\Models\Opportunity;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class Dashboard extends Component
{
    use HasCurrentTeam;

    public int $totalCustomers = 0;

    public int $totalOpportunities = 0;

    public int $openOpportunities = 0;

    public int $closedOpportunities = 0;

    public function mount(): void
    {
        $this->totalCustomers = Customer::count();
        $this->totalOpportunities = Opportunity::count();
        $this->openOpportunities = Opportunity::whereIn('stage', OpportunityStage::getActiveStages())->count();
        $this->closedOpportunities = Opportunity::whereIn('stage', OpportunityStage::getClosedStages())->count();
    }

    public function render(): View
    {
        return view('livewire.dashboard');
    }
}
