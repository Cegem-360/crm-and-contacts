<?php

declare(strict_types=1);

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Support\Facades\Notification;

it('notifies assignee when a task is created with assigned_to', function (): void {
    Notification::fake();

    $assignee = User::factory()->create();
    $task = Task::factory()->create(['assigned_to' => $assignee->id]);

    Notification::assertSentTo($assignee, TaskAssignedNotification::class, function (TaskAssignedNotification $notification) use ($task): bool {
        return $notification->task->id === $task->id;
    });
});

it('does not notify when task is created without assigned_to', function (): void {
    Notification::fake();

    Task::factory()->create(['assigned_to' => null]);

    Notification::assertNothingSent();
});

it('notifies new assignee when assigned_to changes', function (): void {
    Notification::fake();

    $originalAssignee = User::factory()->create();
    $task = Task::factory()->create(['assigned_to' => $originalAssignee->id]);

    Notification::fake();

    $newAssignee = User::factory()->create();
    $task->update(['assigned_to' => $newAssignee->id]);

    Notification::assertSentTo($newAssignee, TaskAssignedNotification::class);
    Notification::assertNotSentTo($originalAssignee, TaskAssignedNotification::class);
});

it('does not notify when other fields change', function (): void {
    Notification::fake();

    $assignee = User::factory()->create();
    Task::factory()->create(['assigned_to' => $assignee->id]);

    Notification::fake();

    Task::first()->update(['title' => 'Updated title']);

    Notification::assertNothingSent();
});
