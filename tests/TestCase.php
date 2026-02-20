<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * @property \App\Models\Team $team
 * @property \App\Models\User $user
 * @property \App\Models\Customer $customer
 * @property string $token
 * @property object $service
 * @property \App\Services\OrderService $orderService
 * @property \App\Services\InvoiceService $invoiceService
 * @property \App\Services\ShipmentService $shipmentService
 * @property \App\Services\ChatService $chatService
 */
abstract class TestCase extends BaseTestCase
{
    //
}
