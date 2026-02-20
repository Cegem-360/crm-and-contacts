<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * @property \App\Models\Team $team
 * @property \App\Models\User $user
 * @property \App\Models\Customer $customer
 * @property \App\Models\Product $product
 * @property \App\Models\Quote $quote
 * @property \App\Models\QuoteTemplate $template
 * @property string $token
 * @property string $pdfPath
 * @property object $service
 * @property \App\Services\OrderService $orderService
 * @property \App\Services\InvoiceService $invoiceService
 * @property \App\Services\ShipmentService $shipmentService
 * @property \App\Services\ChatService $chatService
 * @property \App\Services\PricingService $pricingService
 */
abstract class TestCase extends BaseTestCase
{
    //
}
