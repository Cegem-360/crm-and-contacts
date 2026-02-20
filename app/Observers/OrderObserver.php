<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Models\Order;

final class OrderObserver
{
    public function created(Order $order): void
    {
        event(new OrderCreated($order));
    }

    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            event(new OrderStatusChanged($order, $order->getRawOriginal('status')));
        }
    }
}
