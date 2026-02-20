<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\OpportunityStageMoved;
use App\Models\Opportunity;

final class OpportunityObserver
{
    public function updated(Opportunity $opportunity): void
    {
        if ($opportunity->wasChanged('stage')) {
            event(new OpportunityStageMoved($opportunity, (string) $opportunity->getRawOriginal('stage')));
        }
    }
}
