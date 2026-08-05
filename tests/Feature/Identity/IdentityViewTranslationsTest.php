<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

/**
 * @return list<string>
 */
function packageIdentityViewStrings(): array
{
    $strings = [];

    foreach (File::files(base_path('vendor/madbox-99/laravel-user-team-sync/resources/views/identity')) as $file) {
        preg_match_all("/__\('([^']+)'\)/", $file->getContents(), $matches);

        $strings = [...$strings, ...$matches[1]];
    }

    return array_values(array_unique($strings));
}

it('translates every user-facing string on the package identity pages', function (): void {
    // These pages are what a user sees when SSO fails: an outage, a refused
    // callback, a data conflict, a missing subscription. Leaving them in
    // English on a Hungarian app is the exact gap that shipped once already on
    // a reference app, with the login button. Enumerating keys by hand is
    // what let it through, so this reads the package's own views instead and
    // fails whenever an upgrade adds a string we have not translated.
    $translations = json_decode((string) file_get_contents(lang_path('hu.json')), true);

    $untranslated = array_values(array_diff(
        packageIdentityViewStrings(),
        array_keys($translations),
    ));

    expect($untranslated)->toBe([]);
});

it('finds strings to check, so the guard above cannot pass vacuously', function (): void {
    // If the package moved or renamed its view directory, the test above would
    // compare two empty arrays and report success forever.
    expect(packageIdentityViewStrings())->not->toBeEmpty();
});
