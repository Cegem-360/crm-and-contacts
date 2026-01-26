<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\LoginResponse as BaseLoginResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

final class LoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        return Redirect::to(route('filament.admin.pages.dashboard'));
    }
}
