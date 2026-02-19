<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\QuoteStatusChanged;
use App\Models\Quote;

final class QuoteObserver
{
    public function updated(Quote $quote): void
    {
        if ($quote->wasChanged('status')) {
            event(new QuoteStatusChanged($quote, (string) $quote->getRawOriginal('status')));
        }
    }
}
