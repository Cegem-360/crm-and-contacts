<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Mirrors what `client` mode's `bootClient()` does at boot time: register
 * `identity.redirect`. Route names added after the framework has already
 * booted aren't picked up by `Route::has()`/`route()` until the name lookup
 * table is rebuilt, which normally happens once during boot.
 */
function registerIdentityRedirectRoute(): void
{
    Route::get('/auth/redirect', fn (): null => null)->name('identity.redirect');
    resolve(Router::class)->getRoutes()->refreshNameLookups();
}

/**
 * phpunit.xml does not pin APP_LOCALE, so the rendered language of a page
 * would otherwise depend on the developer's own `.env`. This app runs the
 * locale switcher in its default 'cookie' mode, where the middleware leaves
 * the locale alone unless a locale cookie is sent — so setting it here is
 * enough, and setting `config('app.locale')` alone would not be.
 */
beforeEach(function (): void {
    App::setLocale('en');
});

it('renders the login page in the default (receiver) mode without offering SSO', function (): void {
    // Production runs `receiver` mode today, and this app's committed config
    // and test environment default to it too. `routes/identity-client.php` —
    // and therefore `identity.redirect` — only exists in `client` mode, so the
    // login page must not assume the route is there. Without the guard in
    // `ssoUrl()` this page 500s, which would remove every way into the app
    // until the mode is switched.
    /** @var TestCase $this */
    expect(config('user-team-sync.mode'))->toBe('receiver');

    $response = $this->get('/app/login');

    $response->assertOk();
    // Asserted on the URL, not the label: the label goes through __() and is
    // translated in production, so a text-only assertion would pass vacuously
    // under a non-English locale and stop guarding anything.
    $response->assertDontSee('/auth/redirect');
    $response->assertSeeHtml('wire:submit="authenticate"');
    $response->assertSee('Enter your work email');
});

it('offers the SSO link when the identity route is registered', function (): void {
    /** @var TestCase $this */
    registerIdentityRedirectRoute();

    $response = $this->get('/app/login');

    $response->assertOk();
    $response->assertSeeHtml('Sign in with your Cégem 360 account');
    $response->assertSeeHtml(route('identity.redirect'));
});

it('labels the SSO button in Hungarian', function (): void {
    // The English label above only proves the button renders. The reference
    // app shipped this button untranslated once; this is the assertion that
    // would have caught it.
    /** @var TestCase $this */
    App::setLocale('hu');
    registerIdentityRedirectRoute();

    $this->get('/app/login')
        ->assertOk()
        ->assertSeeHtml('Belépés Cégem 360 fiókkal')
        ->assertDontSeeHtml('Sign in with your Cégem 360 account');
});

it('keeps the password form regardless of whether SSO is offered', function (): void {
    /** @var TestCase $this */
    $this->get('/app/login')
        ->assertOk()
        ->assertSeeHtml('wire:submit="authenticate"')
        ->assertSee('Enter your work email');

    registerIdentityRedirectRoute();

    $this->get('/app/login')
        ->assertOk()
        ->assertSeeHtml('wire:submit="authenticate"')
        ->assertSee('Enter your work email');
});
