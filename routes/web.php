<?php

declare(strict_types=1);

use App\Http\Controllers\ChatDemoController;
use App\Http\Controllers\QuoteViewController;
use App\Livewire\ComplaintSubmission;
use App\Models\Team;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory|View => view('home'))->name('home');

// Guest routes - redirect to Filament auth pages
Route::middleware(['guest'])->group(function (): void {
    Route::get('/login', fn (): Redirector|RedirectResponse => to_route('filament.admin.auth.login'))->name('login');
    Route::get('/register', fn (): Redirector|RedirectResponse => to_route('filament.admin.auth.register'))->name('register');
});

// Dashboard redirect - sends user to their first team's dashboard
Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', function (): Redirector|RedirectResponse {
        $user = Auth::user();
        $team = $user?->teams()->first();

        if ($team instanceof Team) {
            return redirect('/app/'.$team->slug);
        }

        return redirect('/app');
    })->name('dashboard.redirect');
});

// Legacy dashboard routes - redirect to Filament admin panel
Route::middleware(['auth', 'verified'])
    ->get('/dashboard/{team:slug}/{path?}', fn (Team $team, ?string $path = null): RedirectResponse => redirect('/app/'.$team->slug.($path ? '/'.$path : ''), 301))
    ->where('path', '.*');

// Chat demo route
Route::get('/chat-demo', [ChatDemoController::class, 'index'])->name('chat.demo');

// Public quote viewing route (no auth required)
Route::get('/quotes/view/{token}', QuoteViewController::class)->name('quotes.public-view');

// Complaint submission route (public - no team required)
Route::livewire('/complaints/submit', ComplaintSubmission::class)->name('complaints.submit');
