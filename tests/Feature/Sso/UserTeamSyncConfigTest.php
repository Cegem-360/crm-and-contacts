<?php

declare(strict_types=1);
use App\Models\Team;

/**
 * The published config had drifted behind the package: it documented only the
 * publisher/receiver/both modes and carried no 'client' section at all. In client
 * mode the service provider boots the identity client and reads that section, so a
 * missing block meant SSO could never work, no matter what the environment said.
 */
it('carries the client section the sso mode reads', function (): void {
    expect(config('user-team-sync.client'))->toBeArray()
        ->and(config('user-team-sync.client'))->toHaveKeys([
            'app_key',
            'identity_url',
            'client_id',
            'client_secret',
            'redirect_uri',
            'legacy_receiver',
            'subscribe_url',
        ]);
});

it('keeps the application team model rather than the package one', function (): void {
    expect(config('user-team-sync.models.team'))->toBe(Team::class);
});

it('handles an inactive user in the app instead of redirecting away', function (): void {
    // Deliberate deviation from the package default: the middleware answers with a
    // 403 and a logout rather than sending the user to another domain.
    expect(config('user-team-sync.receiver.inactive_redirect_url'))->toBeNull();
});

it('lets an inactive user still reach the logout route', function (): void {
    // Without these patterns the subscription middleware would block logout too,
    // leaving an inactive user with no way out of the panel.
    expect(config('user-team-sync.receiver.bypass_route_patterns'))
        ->toContain('filament.*.auth.logout');
});
