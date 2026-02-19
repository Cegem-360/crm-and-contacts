<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Models\Team;
use Illuminate\Support\Facades\View;

trait HasCurrentTeam
{
    public ?Team $team = null;

    public function bootHasCurrentTeam(): void
    {
        if ($this->team === null) {
            $this->team = request()->attributes->get('current_team')
                ?? (app()->bound('current_team') ? resolve('current_team') : null);
        }

        if ($this->team instanceof Team) {
            app()->instance('current_team', $this->team);
        }

        View::share('currentTeam', $this->team);
    }

    public function getCurrentTeam(): ?Team
    {
        return $this->team;
    }

    public function teamRoute(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        $params = is_array($parameters) ? $parameters : ['model' => $parameters];

        return route($name, array_merge(['team' => $this->team], $params), $absolute);
    }
}
