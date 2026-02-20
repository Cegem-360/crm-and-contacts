<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Models\User;
use App\Notifications\OrderStatusChangedNotification;

final class SendOrderStatusNotification
{
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;
        $previousStatus = $event->previousStatus;

        $teamUsers = User::query()
            ->whereHas('teams', fn ($q) => $q->where('teams.id', $order->team_id))
            ->get();

        foreach ($teamUsers as $user) {
            $user->notify(new OrderStatusChangedNotification($order, $previousStatus));
        }
    }
}
