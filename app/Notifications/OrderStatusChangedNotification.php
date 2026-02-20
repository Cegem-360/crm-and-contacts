<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $previousStatus,
    ) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Order Status Changed: :number', ['number' => $this->order->order_number]))
            ->line(__('An order status has been updated.'))
            ->line('**'.__('Order').':** '.$this->order->order_number)
            ->line('**'.__('Customer').':** '.$this->order->customer?->name)
            ->line('**'.__('Previous Status').':** '.ucfirst($this->previousStatus))
            ->line('**'.__('New Status').':** '.$this->order->status->getLabel())
            ->line('**'.__('Total').':** '.number_format((float) $this->order->total, 2).' Ft');
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->order->status->value,
            'customer_name' => $this->order->customer?->name,
        ];
    }
}
