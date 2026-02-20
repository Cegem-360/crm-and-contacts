<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Services\QuoteTemplateService;
use Illuminate\Contracts\View\View;

final class QuoteViewController extends Controller
{
    public function __invoke(string $token, QuoteTemplateService $service): View
    {
        $quote = Quote::query()
            ->withoutGlobalScopes()
            ->where('view_token', $token)
            ->with(['customer.addresses', 'items.product'])
            ->firstOrFail();

        $service->markAsViewed($quote);

        return view('quotes.public-view', ['quote' => $quote]);
    }
}
