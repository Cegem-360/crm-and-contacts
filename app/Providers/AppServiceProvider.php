<?php

declare(strict_types=1);

namespace App\Providers;

use App\Filament\Commands\FileGenerators\Resources\ResourceClassGenerator;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegistrationResponse;
use App\Models\Campaign;
use App\Models\Customer;
use App\Models\Opportunity;
use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse as RegistrationResponseContract;
use Filament\Commands\FileGenerators\Resources\ResourceClassGenerator as BaseResourceClassGenerator;
use Filament\Support\Facades\FilamentTimezone;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(RegistrationResponseContract::class, RegistrationResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentTimezone::set('Europe/Budapest');
        $this->app->bind(BaseResourceClassGenerator::class, ResourceClassGenerator::class);
        Relation::enforceMorphMap([
            'user' => User::class,
            'customer' => Customer::class,
            'opportunity' => Opportunity::class,
            'campaign' => Campaign::class,
        ]);
    }
}
