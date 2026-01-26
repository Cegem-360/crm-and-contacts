<?php

declare(strict_types=1);

use App\Http\Controllers\ChatDemoController;
use App\Livewire\ComplaintSubmission;
use App\Livewire\Dashboard;
use App\Livewire\Pages\Customers\ListCustomers;
use App\Livewire\Pages\Opportunities\ListOpportunities;
use App\Livewire\Pages\Orders\ListOrders;
use App\Livewire\Pages\Quotes\ListQuotes;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory|View => view('home'))->name('home');

// Guest routes - redirect to Filament auth pages
Route::middleware(['guest'])->group(function (): void {
    Route::get('/login', fn (): Redirector|RedirectResponse => to_route('filament.admin.auth.login'))->name('login');
    Route::get('/register', fn (): Redirector|RedirectResponse => to_route('filament.admin.auth.register'))->name('register');
});

// User Dashboard routes
Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Sales pages
    Route::get('/dashboard/opportunities', ListOpportunities::class)->name('dashboard.opportunities');
    Route::get('/dashboard/customers', ListCustomers::class)->name('dashboard.customers');
    Route::get('/dashboard/quotes', ListQuotes::class)->name('dashboard.quotes');
    Route::get('/dashboard/orders', ListOrders::class)->name('dashboard.orders');
});

// Language switch route
Route::get('/language/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'hu'], true)) {
        abort(400);
    }
    $cookie = cookie('locale', $locale, 60 * 24 * 365);
    $referer = request()->headers->get('referer');
    $redirectUrl = $referer ?: url()->previous();

    return redirect($redirectUrl)->withCookie($cookie);
})->name('language.switch');

// Chat demo route
Route::get('/chat-demo', [ChatDemoController::class, 'index'])->name('chat.demo');

// Complaint submission route
Route::get('/complaints/submit', ComplaintSubmission::class)->name('complaints.submit');
