<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class TaskDeadlineNotification extends Notification
{
    use Queueable;

    public function __construct(public Task $task) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $daysLeft = (int) now()->diffInDays($this->task->due_date, false);

        return (new MailMessage)
            ->subject(__('Task Deadline Approaching: :title', ['title' => $this->task->title]))
            ->line(__('A task deadline is approaching in :days days.', ['days' => $daysLeft]))
            ->line('**'.__('Task').':** '.$this->task->title)
            ->line('**'.__('Priority').':** '.ucfirst($this->task->priority))
            ->line('**'.__('Due Date').':** '.$this->task->due_date->format('Y-m-d'))
            ->when($this->task->description, fn (MailMessage $message) => $message->line('**'.__('Description').':** '.$this->task->description))
            ->line(__('Please ensure this task is completed on time.'));
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'priority' => $this->task->priority,
            'due_date' => $this->task->due_date->format('Y-m-d'),
        ];
    }
}
