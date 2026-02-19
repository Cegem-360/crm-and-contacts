<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegistrationResponse;
use Illuminate\Database\Eloquent\Model;

arch()->preset()->php();
// arch()->preset()->strict();
arch()->preset()->laravel()->ignoring([
    AuthController::class,
    LoginResponse::class,
    RegistrationResponse::class,
    'App\Http\Middleware\ApplyTenantScopes',
]);
arch()->preset()->security();
arch()->expect('App\Models')
    ->toExtend(Model::class)
    ->ignoring('App\Models\Concerns')
    ->ignoring('App\Models\Scopes');
arch()->expect('App\Controllers\Controller')->toBeAbstract();
