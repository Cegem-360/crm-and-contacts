<?php

declare(strict_types=1);

use App\Http\Controllers\ChatDemoController;
use App\Http\Middleware\SetFrontendTenant;
use App\Livewire\ComplaintSubmission;
use App\Livewire\Dashboard;
use App\Livewire\Pages\Campaigns\ListCampaigns;
use App\Livewire\Pages\Campaigns\ViewCampaign;
use App\Livewire\Pages\ChatSessions\ListChatSessions;
use App\Livewire\Pages\Complaints\EditComplaint;
use App\Livewire\Pages\Complaints\ListComplaints;
use App\Livewire\Pages\Complaints\ViewComplaint;
use App\Livewire\Pages\Customers\EditCustomer;
use App\Livewire\Pages\Customers\ListCustomers;
use App\Livewire\Pages\Customers\ViewCustomer;
use App\Livewire\Pages\Discounts\EditDiscount;
use App\Livewire\Pages\Discounts\ListDiscounts;
use App\Livewire\Pages\Discounts\ViewDiscount;
use App\Livewire\Pages\Interactions\EditInteraction;
use App\Livewire\Pages\Interactions\ListInteractions;
use App\Livewire\Pages\Interactions\ViewInteraction;
use App\Livewire\Pages\Invoices\EditInvoice;
use App\Livewire\Pages\Invoices\ListInvoices;
use App\Livewire\Pages\Invoices\ViewInvoice;
use App\Livewire\Pages\Opportunities\EditOpportunity;
use App\Livewire\Pages\Opportunities\ListOpportunities;
use App\Livewire\Pages\Orders\EditOrder;
use App\Livewire\Pages\Orders\ListOrders;
use App\Livewire\Pages\Orders\ViewOrder;
use App\Livewire\Pages\ProductCategories\EditProductCategory;
use App\Livewire\Pages\ProductCategories\ListProductCategories;
use App\Livewire\Pages\Products\EditProduct;
use App\Livewire\Pages\Products\ListProducts;
use App\Livewire\Pages\Products\ViewProduct;
use App\Livewire\Pages\Quotes\EditQuote;
use App\Livewire\Pages\Quotes\ListQuotes;
use App\Livewire\Pages\Quotes\ViewQuote;
use App\Livewire\Pages\Shipments\ListShipments;
use App\Livewire\Pages\Tasks\EditTask;
use App\Livewire\Pages\Tasks\ListTasks;
use App\Livewire\Pages\Tasks\ViewTask;
use App\Livewire\SalesDashboard;
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
            return to_route('dashboard', ['team' => $team]);
        }

        return to_route('filament.admin.tenant-registration');
    })->name('dashboard.redirect');
});

// Team-scoped dashboard routes
Route::middleware(['auth', 'verified', SetFrontendTenant::class])
    ->prefix('/dashboard/{team:slug}')
    ->group(function (): void {
        Route::get('/', Dashboard::class)->name('dashboard');
        Route::get('/sales-dashboard', SalesDashboard::class)->name('dashboard.sales-dashboard');

        // CRM
        Route::get('/customers', ListCustomers::class)->name('dashboard.customers');
        Route::get('/customers/create', EditCustomer::class)->name('dashboard.customers.create');
        Route::get('/customers/{customer}', ViewCustomer::class)->name('dashboard.customers.view');
        Route::get('/customers/{customer}/edit', EditCustomer::class)->name('dashboard.customers.edit');

        // Opportunities (pipeline)
        Route::get('/opportunities', ListOpportunities::class)->name('dashboard.opportunities');
        Route::get('/opportunities/create', EditOpportunity::class)->name('dashboard.opportunities.create');
        Route::get('/opportunities/{opportunity}/edit', EditOpportunity::class)->name('dashboard.opportunities.edit');

        // Quotes
        Route::get('/quotes', ListQuotes::class)->name('dashboard.quotes');
        Route::get('/quotes/create', EditQuote::class)->name('dashboard.quotes.create');
        Route::get('/quotes/{quote}', ViewQuote::class)->name('dashboard.quotes.view');
        Route::get('/quotes/{quote}/edit', EditQuote::class)->name('dashboard.quotes.edit');

        // Orders
        Route::get('/orders', ListOrders::class)->name('dashboard.orders');
        Route::get('/orders/create', EditOrder::class)->name('dashboard.orders.create');
        Route::get('/orders/{order}', ViewOrder::class)->name('dashboard.orders.view');
        Route::get('/orders/{order}/edit', EditOrder::class)->name('dashboard.orders.edit');

        // Invoices
        Route::get('/invoices', ListInvoices::class)->name('dashboard.invoices');
        Route::get('/invoices/create', EditInvoice::class)->name('dashboard.invoices.create');
        Route::get('/invoices/{invoice}', ViewInvoice::class)->name('dashboard.invoices.view');
        Route::get('/invoices/{invoice}/edit', EditInvoice::class)->name('dashboard.invoices.edit');

        // Shipments
        Route::get('/shipments', ListShipments::class)->name('dashboard.shipments');

        // Products
        Route::get('/products', ListProducts::class)->name('dashboard.products');
        Route::get('/products/create', EditProduct::class)->name('dashboard.products.create');
        Route::get('/products/{product}', ViewProduct::class)->name('dashboard.products.view');
        Route::get('/products/{product}/edit', EditProduct::class)->name('dashboard.products.edit');

        // Product Categories
        Route::get('/product-categories', ListProductCategories::class)->name('dashboard.product-categories');
        Route::get('/product-categories/create', EditProductCategory::class)->name('dashboard.product-categories.create');
        Route::get('/product-categories/{productCategory}/edit', EditProductCategory::class)->name('dashboard.product-categories.edit');

        // Discounts
        Route::get('/discounts', ListDiscounts::class)->name('dashboard.discounts');
        Route::get('/discounts/create', EditDiscount::class)->name('dashboard.discounts.create');
        Route::get('/discounts/{discount}', ViewDiscount::class)->name('dashboard.discounts.view');
        Route::get('/discounts/{discount}/edit', EditDiscount::class)->name('dashboard.discounts.edit');

        // Tasks
        Route::get('/tasks', ListTasks::class)->name('dashboard.tasks');
        Route::get('/tasks/create', EditTask::class)->name('dashboard.tasks.create');
        Route::get('/tasks/{task}', ViewTask::class)->name('dashboard.tasks.view');
        Route::get('/tasks/{task}/edit', EditTask::class)->name('dashboard.tasks.edit');

        // Complaints
        Route::get('/complaints', ListComplaints::class)->name('dashboard.complaints');
        Route::get('/complaints/create', EditComplaint::class)->name('dashboard.complaints.create');
        Route::get('/complaints/{complaint}', ViewComplaint::class)->name('dashboard.complaints.view');
        Route::get('/complaints/{complaint}/edit', EditComplaint::class)->name('dashboard.complaints.edit');

        // Interactions
        Route::get('/interactions', ListInteractions::class)->name('dashboard.interactions');
        Route::get('/interactions/create', EditInteraction::class)->name('dashboard.interactions.create');
        Route::get('/interactions/{interaction}', ViewInteraction::class)->name('dashboard.interactions.view');
        Route::get('/interactions/{interaction}/edit', EditInteraction::class)->name('dashboard.interactions.edit');

        // Chat Sessions
        Route::get('/chat-sessions', ListChatSessions::class)->name('dashboard.chat-sessions');

        // Campaigns
        Route::get('/campaigns', ListCampaigns::class)->name('dashboard.campaigns');
        Route::get('/campaigns/{campaign}', ViewCampaign::class)->name('dashboard.campaigns.view');
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

// Complaint submission route (public - no team required)
Route::get('/complaints/submit', ComplaintSubmission::class)->name('complaints.submit');
