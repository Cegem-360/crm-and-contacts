<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Scopes\TeamScope;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

trait BelongsToTeam
{
    public static function bootBelongsToTeam(): void
    {
        static::addGlobalScope(new TeamScope());

        static::creating(static function ($model): void {
            if (empty($model->team_id) && App::bound(Team::CONTAINER_BINDING)) {
                $team = App::make(Team::CONTAINER_BINDING);

                if ($team instanceof Team) {
                    $model->team_id = $team->getKey();
                }
            }
        });
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
