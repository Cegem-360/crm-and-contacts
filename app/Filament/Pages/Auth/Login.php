<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BasePage;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Route;
use Override;

final class Login extends BasePage
{
    public string $view = 'filament.pages.auth.login';

    protected static string $layout = 'filament.layouts.auth';

    /**
     * During the rollout the password form stays for everyone who is not on
     * the SSO allowlist yet, so this is an extra way in, not a replacement.
     *
     * `identity.redirect` only exists in `client` mode (it's registered by the
     * package's `bootClient()`). Returning `null` when it's absent — receiver
     * mode, which is what production runs today — keeps the login page, and
     * the password form on it, from breaking before the mode is switched.
     */
    public function ssoUrl(): ?string
    {
        return Route::has('identity.redirect') ? route('identity.redirect') : null;
    }

    #[Override]
    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('email')
            ->label(__('Enter your work email'))
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->placeholder('example@company.com')
            ->extraInputAttributes(['tabindex' => 1]);
    }

    #[Override]
    protected function getPasswordFormComponent(): TextInput
    {
        return TextInput::make('password')
            ->label(__('Password'))
            ->password()
            ->revealable()
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    #[Override]
    protected function getRememberFormComponent(): Checkbox
    {
        return Checkbox::make('remember')
            ->label(__('Remember me'))
            ->extraInputAttributes(['tabindex' => 3]);
    }
}
