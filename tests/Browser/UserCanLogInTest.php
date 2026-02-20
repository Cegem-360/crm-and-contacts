<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('can sign in the user', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    $user->teams()->attach($team);

    $page = visit('/admin/login');

    $page->assertSee('Sign in')
        ->assertNoJavaScriptErrors()
        ->type('#form\\.email', 'test@example.com')
        ->type('#form\\.password', 'password')
        ->submit()
        ->wait(2)
        ->assertPathIs('/dashboard/'.$team->slug)
        ->assertSee($user->name)
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('auth/login-success'), fullPage: true);

    $this->assertAuthenticated();
});

it('cannot sign in with invalid credentials', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    $user->teams()->attach($team);

    $page = visit('/admin/login');

    $page->assertSee('Sign in')
        ->assertNoJavaScriptErrors()
        ->type('#form\\.email', 'test@example.com')
        ->type('#form\\.password', 'wrong-password')
        ->submit()
        ->wait(1)
        ->assertPathIs('/admin/login')
        ->assertSee('Sign in')
        ->assertNoJavaScriptErrors()
        ->screenshot(filename: screenshotPath('auth/login-failed'), fullPage: true);

    $this->assertGuest();
});
