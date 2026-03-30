<?php

declare(strict_types=1);

use App\Ai\Agents\CrmAssistant;
use App\Filament\Pages\CrmAssistantChat;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->user->teams()->attach($this->team);

    $this->actingAs($this->user);
    Filament::setTenant($this->team);
    Filament::bootCurrentPanel();
});

it('can render the assistant chat page', function (): void {
    livewire(CrmAssistantChat::class)
        ->assertSuccessful();
});

it('validates message is required', function (): void {
    livewire(CrmAssistantChat::class)
        ->set('message', '')
        ->call('sendMessage')
        ->assertHasErrors(['message' => 'required']);
});

it('sends a message and receives a response', function (): void {
    CrmAssistant::fake([
        'You currently have 10 active customers.',
    ]);

    livewire(CrmAssistantChat::class)
        ->set('message', 'How many active customers do I have?')
        ->call('sendMessage')
        ->assertSet('message', '')
        ->assertSee('How many active customers do I have?')
        ->assertSee('10 active customers');
});

it('handles agent errors gracefully', function (): void {
    CrmAssistant::fake(fn () => throw new RuntimeException('API Error'));

    livewire(CrmAssistantChat::class)
        ->set('message', 'test question')
        ->call('sendMessage')
        ->assertSee('Sorry, an error occurred')
        ->assertNotified();
});

it('starts a new conversation', function (): void {
    CrmAssistant::fake(['Hello!']);

    $component = livewire(CrmAssistantChat::class)
        ->set('message', 'Hi')
        ->call('sendMessage');

    $component->call('newConversation')
        ->assertSet('chatHistory', [])
        ->assertSet('conversationId', null)
        ->assertSet('message', '');
});

it('lists conversations in sidebar', function (): void {
    $conversationId = Str::uuid7()->toString();

    DB::table('agent_conversations')->insert([
        'id' => $conversationId,
        'user_id' => $this->user->id,
        'title' => 'Test Conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    livewire(CrmAssistantChat::class)
        ->assertSee('Test Conversation');
});

it('loads a previous conversation', function (): void {
    $conversationId = Str::uuid7()->toString();

    DB::table('agent_conversations')->insert([
        'id' => $conversationId,
        'user_id' => $this->user->id,
        'title' => 'Previous Chat',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('agent_conversation_messages')->insert([
        'id' => Str::uuid7()->toString(),
        'conversation_id' => $conversationId,
        'user_id' => $this->user->id,
        'agent' => CrmAssistant::class,
        'role' => 'user',
        'content' => 'Hello AI',
        'attachments' => '[]',
        'tool_calls' => '[]',
        'tool_results' => '[]',
        'usage' => '{}',
        'meta' => '{}',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('agent_conversation_messages')->insert([
        'id' => Str::uuid7()->toString(),
        'conversation_id' => $conversationId,
        'user_id' => $this->user->id,
        'agent' => CrmAssistant::class,
        'role' => 'assistant',
        'content' => 'Hello! How can I help?',
        'attachments' => '[]',
        'tool_calls' => '[]',
        'tool_results' => '[]',
        'usage' => '{}',
        'meta' => '{}',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    livewire(CrmAssistantChat::class)
        ->call('loadConversation', $conversationId)
        ->assertSet('conversationId', $conversationId)
        ->assertSee('Hello AI')
        ->assertSee('Hello! How can I help?');
});

it('deletes a conversation', function (): void {
    $conversationId = Str::uuid7()->toString();

    DB::table('agent_conversations')->insert([
        'id' => $conversationId,
        'user_id' => $this->user->id,
        'title' => 'To Delete',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('agent_conversation_messages')->insert([
        'id' => Str::uuid7()->toString(),
        'conversation_id' => $conversationId,
        'user_id' => $this->user->id,
        'agent' => CrmAssistant::class,
        'role' => 'user',
        'content' => 'test',
        'attachments' => '[]',
        'tool_calls' => '[]',
        'tool_results' => '[]',
        'usage' => '{}',
        'meta' => '{}',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    livewire(CrmAssistantChat::class)
        ->call('loadConversation', $conversationId)
        ->assertSet('conversationId', $conversationId)
        ->call('deleteConversation', $conversationId)
        ->assertSet('conversationId', null)
        ->assertSet('chatHistory', [])
        ->assertNotified();

    $this->assertDatabaseMissing('agent_conversations', ['id' => $conversationId]);
    $this->assertDatabaseMissing('agent_conversation_messages', ['conversation_id' => $conversationId]);
});

it('does not show conversations from other users', function (): void {
    $otherUser = User::factory()->create();

    DB::table('agent_conversations')->insert([
        'id' => Str::uuid7()->toString(),
        'user_id' => $otherUser->id,
        'title' => 'Other User Chat',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    livewire(CrmAssistantChat::class)
        ->assertDontSee('Other User Chat');
});
