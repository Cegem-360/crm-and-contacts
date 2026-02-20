<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class QuoteExpiringNotification extends Notification
{
    use Queueable;

    public function __construct(public Quote $quote) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $daysLeft = (int) now()->diffInDays($this->quote->valid_until, false);

        return (new MailMessage)
            ->subject(__('Quote Expiring Soon: :number', ['number' => $this->quote->quote_number]))
            ->line(__('A quote is expiring in :days days.', ['days' => $daysLeft]))
            ->line('**'.__('Quote').':** '.$this->quote->quote_number)
            ->line('**'.__('Customer').':** '.$this->quote->customer?->name)
            ->line('**'.__('Total').':** '.number_format((float) $this->quote->total, 2).' Ft')
            ->line('**'.__('Valid Until').':** '.$this->quote->valid_until->format('Y-m-d'))
            ->line(__('Please follow up with the customer.'));
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'quote_id' => $this->quote->id,
            'quote_number' => $this->quote->quote_number,
            'customer_name' => $this->quote->customer?->name,
            'valid_until' => $this->quote->valid_until->format('Y-m-d'),
            'total' => (float) $this->quote->total,
        ];
    }
}
