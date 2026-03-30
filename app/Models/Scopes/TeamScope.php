<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\App;

final class TeamScope implements Scope
{
    /** @param Builder<Model> $builder */
    public function apply(Builder $builder, Model $model): void
    {
        $team = App::bound(Team::CONTAINER_BINDING)
            ? App::make(Team::CONTAINER_BINDING)
            : null;

        if ($team instanceof Team) {
            $builder->where(
                $model->qualifyColumn('team_id'),
                $team->getKey(),
            );
        }
    }
}
