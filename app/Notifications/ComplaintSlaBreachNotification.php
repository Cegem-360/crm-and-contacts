<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ComplaintSlaBreachNotification extends Notification
{
    use Queueable;

    public function __construct(public Complaint $complaint) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $overdueHours = $this->complaint->sla_deadline_at
            ? (int) $this->complaint->sla_deadline_at->diffInHours(now())
            : 0;

        return (new MailMessage)
            ->subject(__('SLA Breach: :number', ['number' => $this->complaint->complaint_number]))
            ->line(__('A complaint has exceeded its SLA deadline by :hours hours.', ['hours' => $overdueHours]))
            ->line('**'.__('Complaint').':** '.$this->complaint->complaint_number)
            ->line('**'.__('Title').':** '.$this->complaint->title)
            ->line('**'.__('Severity').':** '.$this->complaint->severity?->value)
            ->line('**'.__('Customer').':** '.$this->complaint->customer?->name)
            ->line('**'.__('SLA Deadline').':** '.$this->complaint->sla_deadline_at?->format('Y-m-d H:i'))
            ->line(__('Immediate attention is required.'));
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'complaint_number' => $this->complaint->complaint_number,
            'title' => $this->complaint->title,
            'severity' => $this->complaint->severity?->value,
            'sla_deadline_at' => $this->complaint->sla_deadline_at?->format('Y-m-d H:i'),
        ];
    }
}
