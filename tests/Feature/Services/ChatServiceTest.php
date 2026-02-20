<?php

declare(strict_types=1);

use App\Enums\ChatMessageSenderType;
use App\Enums\ChatSessionStatus;
use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Customer;
use App\Models\Team;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->customer = Customer::factory()->for($this->team)->create();
    $this->user = User::factory()->create();
    $this->user->teams()->attach($this->team);
    $this->service = resolve(ChatService::class);
});

it('starts a session for a customer', function (): void {
    $session = $this->service->startSession($this->customer);

    expect($session)->toBeInstanceOf(ChatSession::class)
        ->and($session->customer_id)->toBe($this->customer->id)
        ->and($session->user_id)->toBeNull()
        ->and($session->status)->toBe(ChatSessionStatus::Active)
        ->and($session->unread_count)->toBe(0);
});

it('starts a session with an assigned user', function (): void {
    $session = $this->service->startSession($this->customer, $this->user);

    expect($session->user_id)->toBe($this->user->id);
});

it('sends a message and broadcasts event', function (): void {
    Event::fake([MessageSent::class]);

    $session = $this->service->startSession($this->customer);

    $message = $this->service->sendMessage(
        $session,
        'Hello!',
        ChatMessageSenderType::Customer,
        $this->customer->id,
    );

    expect($message)->toBeInstanceOf(ChatMessage::class)
        ->and($message->message)->toBe('Hello!')
        ->and($message->sender_type)->toBe(ChatMessageSenderType::Customer)
        ->and($message->is_read)->toBeFalse();

    $session->refresh();
    expect($session->last_message_at)->not->toBeNull()
        ->and($session->unread_count)->toBe(1);

    Event::assertDispatched(MessageSent::class);
});

it('does not increment unread count for user messages', function (): void {
    Event::fake([MessageSent::class]);

    $session = $this->service->startSession($this->customer, $this->user);

    $this->service->sendMessage(
        $session,
        'Hi there',
        ChatMessageSenderType::User,
        $this->user->id,
    );

    $session->refresh();
    expect($session->unread_count)->toBe(0);
});

it('sends a reply to a parent message', function (): void {
    Event::fake([MessageSent::class]);

    $session = $this->service->startSession($this->customer);
    $parent = $this->service->sendMessage($session, 'Parent', ChatMessageSenderType::Customer, $this->customer->id);
    $reply = $this->service->sendMessage($session, 'Reply', ChatMessageSenderType::User, $this->user->id, $parent->id);

    expect($reply->parent_message_id)->toBe($parent->id);
});

it('marks a message as read and decrements unread count', function (): void {
    Event::fake([MessageSent::class]);

    $session = $this->service->startSession($this->customer);
    $message = $this->service->sendMessage($session, 'Hello', ChatMessageSenderType::Customer, $this->customer->id);

    $this->service->markMessageAsRead($message);

    $message->refresh();
    $session->refresh();

    expect($message->is_read)->toBeTrue()
        ->and($message->read_at)->not->toBeNull()
        ->and($session->unread_count)->toBe(0);
});

it('does not decrement unread count below zero', function (): void {
    Event::fake([MessageSent::class]);

    $session = $this->service->startSession($this->customer);
    $message = $this->service->sendMessage($session, 'Hello', ChatMessageSenderType::User, $this->user->id);

    expect($session->fresh()->unread_count)->toBe(0);

    $this->service->markMessageAsRead($message);

    expect($session->fresh()->unread_count)->toBe(0);
});

it('marks all messages as read for a sender type', function (): void {
    Event::fake([MessageSent::class]);

    $session = $this->service->startSession($this->customer);
    $this->service->sendMessage($session, 'Msg 1', ChatMessageSenderType::Customer, $this->customer->id);
    $this->service->sendMessage($session, 'Msg 2', ChatMessageSenderType::Customer, $this->customer->id);

    $this->service->markAllMessagesAsRead($session, ChatMessageSenderType::Customer);

    $session->refresh();
    expect($session->unread_count)->toBe(0);

    $unread = ChatMessage::query()
        ->where('chat_session_id', $session->id)
        ->where('is_read', false)
        ->count();

    expect($unread)->toBe(0);
});

it('closes a session', function (): void {
    $session = $this->service->startSession($this->customer);

    $this->service->closeSession($session);

    $session->refresh();
    expect($session->status)->toBe(ChatSessionStatus::Closed)
        ->and($session->ended_at)->not->toBeNull();
});

it('transfers a session to another user', function (): void {
    $newUser = User::factory()->create();
    $session = $this->service->startSession($this->customer, $this->user);

    $this->service->transferSession($session, $newUser);

    $session->refresh();
    expect($session->user_id)->toBe($newUser->id)
        ->and($session->status)->toBe(ChatSessionStatus::Transferred);
});

it('rates a session with valid rating', function (): void {
    $session = $this->service->startSession($this->customer);

    $this->service->rateSession($session, 4);

    expect($session->fresh()->rating)->toBe(4);
});

it('throws exception for invalid rating', function (): void {
    $session = $this->service->startSession($this->customer);

    $this->service->rateSession($session, 6);
})->throws(InvalidArgumentException::class, 'Rating must be between 1 and 5');

it('returns active sessions', function (): void {
    ChatSession::factory()->for($this->team)->for($this->customer)->create([
        'status' => ChatSessionStatus::Active,
    ]);
    ChatSession::factory()->for($this->team)->for($this->customer)->create([
        'status' => ChatSessionStatus::Closed,
    ]);

    $active = $this->service->getActiveSessions();

    expect($active)->toHaveCount(1)
        ->and($active->first()->status)->toBe(ChatSessionStatus::Active);
});

it('returns unassigned sessions', function (): void {
    ChatSession::factory()->for($this->team)->for($this->customer)->create([
        'status' => ChatSessionStatus::Active,
        'user_id' => null,
    ]);
    ChatSession::factory()->for($this->team)->for($this->customer)->create([
        'status' => ChatSessionStatus::Active,
        'user_id' => $this->user->id,
    ]);

    $unassigned = $this->service->getUnassignedSessions();

    expect($unassigned)->toHaveCount(1)
        ->and($unassigned->first()->user_id)->toBeNull();
});

it('assigns a session to a user', function (): void {
    $session = $this->service->startSession($this->customer);

    $this->service->assignSession($session, $this->user);

    expect($session->fresh()->user_id)->toBe($this->user->id);
});
