<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Madbox99\UserTeamSync\Client\Http\Middleware\RevalidateIdentity;
use Madbox99\UserTeamSync\Receiver\Http\Middleware\EnsureUserHasActiveSubscription;
use Spatie\Permission\Models\Role as SpatieRole;

it('runs the identity revalidation after authentication and before the subscription gate', function (): void {
    // Order matters: a guest has nothing to revalidate, and a subscription
    // restored at the hub must take effect on the same request that checks it.
    // Asserted with toBe(), not toContain(): toContain() would still pass with
    // the array fully reversed, which is exactly the failure being guarded.
    $panel = Filament::getPanel('admin');

    expect($panel->getAuthMiddleware())->toBe([
        Authenticate::class,
        RevalidateIdentity::class,
        EnsureUserHasActiveSubscription::class,
    ]);
});

it('no longer offers tenant registration', function (): void {
    // In client mode a team created here would carry no uuid, so no publisher
    // org could ever match it. The very next revalidation calls sync() with
    // the claim's orgs only, and detaches the user from the team they just
    // created — they lose access to their own new tenant within 15 minutes.
    // Teams are created on the publisher and arrive in the claims.
    expect(Filament::getPanel('admin')->getTenantRegistrationPage())->toBeNull();
});

it('maps the publisher role names onto the local ones', function (): void {
    // The publisher sends lower-case names; this app's Spatie roles are
    // capitalised. MySQL's collation hides the difference in production,
    // SQLite (which these tests run on) does not.
    //
    // 'subscriber' => 'Support' is a PLACEHOLDER, not a decision: this app has
    // no basic-subscriber role, and 'Support' is merely the narrowest of the
    // four that exist. Pinned here so that confirming the real mapping is a
    // deliberate edit to a failing test rather than a silent config tweak.
    expect(config('user-team-sync.client.role_map'))->toBe([
        'admin' => 'Admin',
        'manager' => 'Manager',
        'subscriber' => 'Support',
    ]);
});

it('keeps the role driver the capitalised role map was written for', function (): void {
    // The map above is capitalised only because roles are Spatie rows here.
    // Flipping this to 'default' would write those capitalised names into a
    // plain users.role column whose vocabulary is the publisher's lower-case
    // one, and nothing would complain.
    expect(config('user-team-sync.receiver.role_driver'))->toBe('spatie');
});

it('only resolves onto roles that exist once the app has been seeded', function (): void {
    // Every path out of IdentityProvisioner::resolveRoleName() — an explicit
    // map hit, the case-insensitive fallback, or the default — ends in
    // syncRoles(), which looks a role up by *name* and throws
    // RoleDoesNotExist when there is none. Unlike a casing mismatch, a role
    // that genuinely does not exist is not something MySQL's case-insensitive
    // collation can paper over: it fails identically everywhere, and the user
    // simply cannot sign in.
    //
    // Asserted against the roles the app's own seeder actually creates, not
    // against App\Enums\Role, so that a name invented in config and never
    // seeded still fails. This is the assertion that catches a 'subscriber' /
    // 'Subscriber' default with no such row behind it.
    $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);

    $seededRoleNames = SpatieRole::query()->pluck('name')->all();

    expect($seededRoleNames)->not->toBeEmpty()
        ->and(array_values(config('user-team-sync.client.role_map')))->each->toBeIn($seededRoleNames)
        ->and(config('user-team-sync.receiver.default_role'))->toBeIn($seededRoleNames);
});

it('seeds exactly the role vocabulary the map was written against', function (): void {
    // Pins the other half of the guard above. The 'Subscriber' role now exists
    // because the legacy receiver push creates members with that role, and
    // without a matching row the push failed with a 500. The SSO `role_map`
    // still sends 'subscriber' to 'Support' on purpose: pointing it at
    // 'Subscriber' would widen SSO users to the sales-rep permission set, and
    // that is a decision to make deliberately, not one to inherit here.
    $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);

    expect(SpatieRole::query()->pluck('name')->sort()->values()->all())->toBe([
        'Admin',
        'Manager',
        'Sales Representative',
        'Subscriber',
        'Support',
    ]);
});

it('declares its own app key so the entitlement check can work', function (): void {
    // Must equal sync_apps.name on the publisher for sales.cegem360.eu.
    expect(config('user-team-sync.client.app_key'))->toBe('ertekesites');
});

it('points the callback and the retry link at this app, not at a reference app', function (): void {
    // This app's Filament panel is mounted on /app, so the package's own
    // '/login' default would send a failed callback to a 404.
    expect(config('user-team-sync.client.redirect_uri'))->toBe('https://sales.cegem360.eu/auth/callback')
        ->and(config('user-team-sync.client.login_url'))->toBe('/app/login');
});

it('keeps the legacy receiver mounted during the rollout', function (): void {
    expect(config('user-team-sync.client.legacy_receiver'))->toBeTrue();
});

it('resolves the connect timeout and retry-after knobs from their env vars', function (): void {
    // `mergeConfigFrom` is a shallow `array_merge`: this app's `client` array
    // replaces the package's wholesale, so any key the package declares that
    // this app does not re-declare silently stops existing. The values below
    // come from phpunit.xml, proving the config file actually reads the env
    // vars rather than being permanently stuck on the package's defaults
    // (3 and 5).
    expect(config('user-team-sync.client.http_connect_timeout'))->toBe('7')
        ->and(config('user-team-sync.client.retry_after_minutes'))->toBe('9');
});

it('declares every client config key the package declares', function (): void {
    // The structural version of the test above. Enumerating keys one at a time
    // has already failed twice on the reference apps: the package gains a key,
    // this app's `client` array silently drops it because `mergeConfigFrom` is
    // a shallow merge, and nothing notices until the env var turns out to be
    // inert in production. This fails the moment a package upgrade adds a key
    // we have not adopted.
    $packageConfig = require base_path('vendor/madbox-99/laravel-user-team-sync/config/user-team-sync.php');

    $missing = array_diff(
        array_keys($packageConfig['client']),
        array_keys(config('user-team-sync.client')),
    );

    expect($missing)->toBe([]);
});
