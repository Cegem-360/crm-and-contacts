<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class LeadInactiveNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Customer $customer,
        public int $inactiveDays,
    ) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Inactive Lead: :name', ['name' => $this->customer->name]))
            ->line(__('A lead has been inactive for :days days.', ['days' => $this->inactiveDays]))
            ->line('**'.__('Customer').':** '.$this->customer->name)
            ->line('**'.__('Email').':** '.$this->customer->email)
            ->line('**'.__('Last Activity').':** '.$this->inactiveDays.' '.__('days ago'))
            ->line(__('Consider reaching out to re-engage this lead.'));
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'customer_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'inactive_days' => $this->inactiveDays,
        ];
    }
}
