<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\OpportunityStage;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
final class KanbanBoard extends Component
{
    use HasCurrentTeam;

    public string $filterAssignee = '';

    public string $filterPeriod = '';

    public string $filterMinValue = '';

    public string $filterMaxValue = '';

    /** @var array<string, array{label: string, color: string, opportunities: array<int, mixed>, count: int, total: float}> */
    public array $stages = [];

    /** @var array<int, array{id: int, name: string}> */
    public array $teamUsers = [];

    public function mount(): void
    {
        $this->loadTeamUsers();
        $this->loadBoard();
    }

    public function updatedFilterAssignee(): void
    {
        $this->loadBoard();
    }

    public function updatedFilterPeriod(): void
    {
        $this->loadBoard();
    }

    public function updatedFilterMinValue(): void
    {
        $this->loadBoard();
    }

    public function updatedFilterMaxValue(): void
    {
        $this->loadBoard();
    }

    public function moveOpportunity(int $opportunityId, string $newStage): void
    {
        $opportunity = Opportunity::query()->find($opportunityId);

        if (! $opportunity) {
            return;
        }

        $stage = OpportunityStage::tryFrom($newStage);

        if (! $stage) {
            return;
        }

        $opportunity->update(['stage' => $stage]);

        $this->loadBoard();
    }

    public function render(): View
    {
        return view('livewire.kanban-board');
    }

    private function loadTeamUsers(): void
    {
        $this->teamUsers = User::query()
            ->whereHas('teams', fn ($q) => $q->where('teams.id', $this->team?->id))
            ->get(['id', 'name'])
            ->map(fn (User $user): array => ['id' => $user->id, 'name' => $user->name])
            ->all();
    }

    private function loadBoard(): void
    {
        $this->stages = [];

        foreach (OpportunityStage::cases() as $stage) {
            $opportunities = $this->getOpportunitiesForStage($stage);

            $this->stages[$stage->value] = [
                'label' => $stage->getLabel(),
                'color' => $stage->getColor(),
                'opportunities' => $opportunities->all(),
                'count' => $opportunities->count(),
                'total' => $opportunities->sum('value'),
            ];
        }
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function getOpportunitiesForStage(OpportunityStage $stage): Collection
    {
        $query = Opportunity::query()
            ->with(['customer', 'assignedUser'])
            ->where('stage', $stage);

        if ($this->filterAssignee !== '') {
            $query->where('assigned_to', (int) $this->filterAssignee);
        }

        if ($this->filterPeriod !== '') {
            $months = (int) $this->filterPeriod;
            $query->where('expected_close_date', '>=', Date::now()->subMonths($months));
        }

        if ($this->filterMinValue !== '') {
            $query->where('value', '>=', (float) $this->filterMinValue);
        }

        if ($this->filterMaxValue !== '') {
            $query->where('value', '<=', (float) $this->filterMaxValue);
        }

        return $query->orderByDesc('value')->get()->map(fn (Opportunity $opp): array => [
            'id' => $opp->id,
            'title' => $opp->title,
            'customer_name' => $opp->customer?->name ?? '—',
            'value' => (float) $opp->value,
            'probability' => $opp->probability,
            'assignee_name' => $opp->assignedUser?->name ?? '—',
            'assignee_initials' => $opp->assignedUser
                ? mb_strtoupper(mb_substr($opp->assignedUser->name, 0, 1))
                : '?',
            'expected_close_date' => $opp->expected_close_date?->format('Y-m-d'),
        ]);
    }
}
