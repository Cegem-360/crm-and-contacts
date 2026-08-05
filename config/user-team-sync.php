<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;

return [
    /*
    |--------------------------------------------------------------------------
    | Sync Mode
    |--------------------------------------------------------------------------
    | 'publisher' - This app sends sync events to other apps (subscriber app)
    | 'receiver'  - This app receives sync events from the publisher
    | 'both'      - This app both sends and receives
    */
    'mode' => env('USER_TEAM_SYNC_MODE', 'receiver'),

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */
    'models' => [
        'user' => env('USER_TEAM_SYNC_USER_MODEL', User::class),
        'team' => env('USER_TEAM_SYNC_TEAM_MODEL', Team::class),
    ],

    /*
    |--------------------------------------------------------------------------
    | Publisher Configuration
    |--------------------------------------------------------------------------
    */
    'publisher' => [
        'api_key' => env('USER_TEAM_SYNC_API_KEY'),

        /*
        |----------------------------------------------------------------------
        | App Source
        |----------------------------------------------------------------------
        | 'config'   - Apps are defined in the 'apps' array below
        | 'database' - Apps are stored in the database (sync_apps table)
        */
        'app_source' => env('USER_TEAM_SYNC_APP_SOURCE', 'config'),
        'apps_table' => 'sync_apps',

        'apps' => [
            // 'crm' => [
            //     'url' => env('CRM_APP_URL'),
            //     'api_key' => env('CRM_APP_API_KEY'),
            //     'active' => true,
            // ],
        ],

        'queue' => env('USER_TEAM_SYNC_QUEUE', 'default'),
        'connection' => env('USER_TEAM_SYNC_QUEUE_CONNECTION'),
        'tries' => env('USER_TEAM_SYNC_TRIES', 3),
        'backoff' => env('USER_TEAM_SYNC_BACKOFF', 60),
        'timeout' => env('USER_TEAM_SYNC_TIMEOUT', 10),

        'auto_observe' => true,
        'sync_fields' => ['email', 'role'],

        /*
        |----------------------------------------------------------------------
        | Team Sync Fields
        |----------------------------------------------------------------------
        | Team fields whose change is propagated to receivers. 'slug' matters
        | most: receivers used to match teams by slug forever after creation, so
        | a rename on the publisher silently broke the cross-app link.
        */
        'team_sync_fields' => ['name', 'slug'],
        'skip_ssl_for_test_domains' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Receiver Configuration
    |--------------------------------------------------------------------------
    */
    'receiver' => [
        'api_key' => env('USER_TEAM_SYNC_API_KEY'),
        'route_prefix' => 'api',
        'middleware' => [],
        'role_driver' => 'spatie',

        /*
        | Must name a role that actually exists in the roles table: both the
        | receiver's UserSyncController and IdentityProvisioner::resolveRoleName()
        | hand this value straight to assignRole()/syncRoles(). It used to be
        | 'subscriber', which matches no row in this app in any casing — the
        | receiver never hit it because its syncs only ever carry admin/manager,
        | but in client mode it is the fallback for every unrecognised claim
        | role, and it would have thrown RoleDoesNotExist.
        |
        | PLACEHOLDER — same open question as 'client.role_map' below: 'Support'
        | is the narrowest existing role, not a confirmed decision.
        */
        'default_role' => 'Support',
        'default_active' => false,
        'inactive_redirect_url' => null, // in-app 403+logout instead of cross-domain redirect

        /*
        |----------------------------------------------------------------------
        | Bypass Route Patterns
        |----------------------------------------------------------------------
        | Route name patterns that the EnsureUserHasActiveSubscription
        | middleware allows through regardless of subscription status.
        | Defaults cover Filament panel logout and a generic 'logout' route
        | so inactive users can always sign out.
        */
        'bypass_route_patterns' => [
            'filament.*.auth.logout',
            'logout',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Client Configuration
    |--------------------------------------------------------------------------
    | Used when mode is 'client': this app delegates authentication to the
    | identity provider and rebuilds its local user state from the token
    | claims on every login and every revalidation.
    |
    | Every key the package declares must be repeated here. `mergeConfigFrom()`
    | is a shallow `array_merge`, so this 'client' array replaces the package's
    | wholesale: a key omitted here simply does not exist, and the env var that
    | was supposed to drive it is silently inert. `SalesSsoWiringTest` fails the
    | moment a package upgrade adds a key this file has not adopted.
    */
    'client' => [
        /*
        | This app's own key. Must equal sync_apps.name on the publisher and
        | the slug of the plan category that grants access to this app. The
        | callback rejects the login when this key is absent from the token's
        | 'apps' claim.
        */
        'app_key' => env('IDENTITY_APP_KEY', 'ertekesites'),

        'identity_url' => env('IDENTITY_URL', 'https://cegem360.eu'),
        'client_id' => env('IDENTITY_CLIENT_ID'),
        'client_secret' => env('IDENTITY_CLIENT_SECRET'),
        'redirect_uri' => env('IDENTITY_REDIRECT_URI', 'https://sales.cegem360.eu/auth/callback'),
        'scopes' => '',
        'http_timeout' => env('IDENTITY_HTTP_TIMEOUT', 10),
        'http_connect_timeout' => env('IDENTITY_HTTP_CONNECT_TIMEOUT', 3),
        'revalidate_after_minutes' => env('IDENTITY_REVALIDATE_MINUTES', 15),
        'retry_after_minutes' => env('IDENTITY_RETRY_MINUTES', 5),
        'grace_hours' => env('IDENTITY_GRACE_HOURS', 24),
        'allowlist' => array_values(array_filter(array_map(
            trim(...),
            explode(',', (string) env('IDENTITY_SSO_ALLOWLIST', '')),
        ))),
        'legacy_receiver' => env('IDENTITY_LEGACY_RECEIVER', true),

        /*
        | The publisher's UserRole enum is lower-case. Like crm — and unlike
        | mes, Storage-cms and workflow — this app runs a real Spatie role
        | layer: 'receiver.role_driver' is 'spatie', the User model uses
        | HasRoles, there is no users.role column at all, and the roles table
        | holds exactly Admin, Manager, Sales Representative and Support. A
        | role is therefore a roles row whose name is capitalised. Production
        | MySQL papers over the case difference with a case-insensitive
        | collation; SQLite (which the tests run on) does not, so the mapping
        | is explicit.
        |
        | 'admin' and 'manager' are unambiguous. 'subscriber' is NOT: this app
        | has no counterpart to the publisher's basic-subscriber role, and a
        | missing role is not something a collation can paper over —
        | syncRoles() throws RoleDoesNotExist and the user cannot sign in at
        | all. 'Support' is used below only because it is the narrowest of the
        | four that exist (view customers, plus complaints, tasks and
        | interactions).
        |
        | PLACEHOLDER — NEEDS THE OWNER'S CONFIRMATION. What a basic subscriber
        | should be allowed to see in a sales CRM is a business decision, not a
        | mechanical one. Do not switch this app to client mode until that
        | mapping is confirmed.
        */
        'role_map' => [
            'admin' => 'Admin',
            'manager' => 'Manager',
            'subscriber' => 'Support',
        ],

        'subscribe_url' => env('IDENTITY_SUBSCRIBE_URL', 'https://cegem360.eu'),

        /*
        | Where the package's callback error pages send a user who should try
        | signing in again. This app's Filament panel is mounted on /app, so
        | its login form is /app/login, not the package's own /login default.
        */
        'login_url' => env('IDENTITY_LOGIN_URL', '/app/login'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => true,
        'table' => 'sync_logs',
        'retention_days' => 30,
    ],
];
