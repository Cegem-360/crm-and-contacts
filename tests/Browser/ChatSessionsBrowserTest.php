<?php

declare(strict_types=1);

use App\Models\ChatSession;
use App\Models\Customer;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function (): void {
    $this->team = Team::factory()->create();
    $this->user = User::factory()->create([
        'email' => 'chatsessions@example.com',
        'password' => Hash::make('password'),
    ]);
    $this->user->teams()->attach($this->team);
});

function loginAndVisitChatSessions(object $context): mixed
{
    $page = visit('/admin/login');

    $page->type('#form\\.email', 'chatsessions@example.com')
        ->type('#form\\.password', 'password')
        ->submit()
        ->wait(2);

    return visit('/dashboard/'.$context->team->slug.'/chat-sessions');
}

it('renders the chat sessions list page', function (): void {
    $page = loginAndVisitChatSessions($this);

    $page->assertPathIs('/dashboard/'.$this->team->slug.'/chat-sessions')
        ->assertSee('Chat Sessions')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('chat-sessions/list'), fullPage: true);
});

it('displays seeded chat sessions in the table', function (): void {
    $customer = Customer::factory()->for($this->team)->create([
        'name' => 'Chat Customer',
    ]);

    ChatSession::factory()->for($customer)->for($this->team)->create([
        'user_id' => $this->user->id,
        'status' => 'active',
    ]);

    $page = loginAndVisitChatSessions($this);

    $page->assertSee('Chat Customer')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('chat-sessions/with-data'), fullPage: true);
});
