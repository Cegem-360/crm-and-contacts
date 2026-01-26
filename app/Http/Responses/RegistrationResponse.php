<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\RegistrationResponse as BaseRegistrationResponse;
use Illuminate\Http\RedirectResponse;

final class RegistrationResponse extends BaseRegistrationResponse
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->route('filament.admin.pages.dashboard');
    }
}
