<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\LoginResponse as BaseLoginResponse;
use Illuminate\Http\RedirectResponse;

final class LoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->route('filament.admin.pages.dashboard');
    }
}
