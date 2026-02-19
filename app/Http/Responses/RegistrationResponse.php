<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\RegistrationResponse as BaseRegistrationResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

final class RegistrationResponse extends BaseRegistrationResponse
{
    public function toResponse($request): RedirectResponse
    {
        return Redirect::to(route('filament.admin.tenant-registration'));
    }
}
