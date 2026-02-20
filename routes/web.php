<?php

declare(strict_types=1);

use App\Http\Controllers\ChatDemoController;
use App\Http\Controllers\QuoteViewController;
use App\Http\Middleware\SetFrontendTenant;
use App\Livewire\ComplaintSubmission;
use App\Livewire\Dashboard;
use App\Livewire\KanbanBoard;
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
        Route::livewire('/', Dashboard::class)->name('dashboard');
        Route::livewire('/sales-dashboard', SalesDashboard::class)->name('dashboard.sales-dashboard');

        // CRM
        Route::livewire('/customers', ListCustomers::class)->name('dashboard.customers');
        Route::livewire('/customers/create', EditCustomer::class)->name('dashboard.customers.create');
        Route::livewire('/customers/{customer}', ViewCustomer::class)->name('dashboard.customers.view');
        Route::livewire('/customers/{customer}/edit', EditCustomer::class)->name('dashboard.customers.edit');

        // Opportunities (pipeline)
        Route::livewire('/opportunities', ListOpportunities::class)->name('dashboard.opportunities');
        Route::livewire('/opportunities/kanban', KanbanBoard::class)->name('dashboard.opportunities.kanban');
        Route::livewire('/opportunities/create', EditOpportunity::class)->name('dashboard.opportunities.create');
        Route::livewire('/opportunities/{opportunity}/edit', EditOpportunity::class)->name('dashboard.opportunities.edit');

        // Quotes
        Route::livewire('/quotes', ListQuotes::class)->name('dashboard.quotes');
        Route::livewire('/quotes/create', EditQuote::class)->name('dashboard.quotes.create');
        Route::livewire('/quotes/{quote}', ViewQuote::class)->name('dashboard.quotes.view');
        Route::livewire('/quotes/{quote}/edit', EditQuote::class)->name('dashboard.quotes.edit');

        // Orders
        Route::livewire('/orders', ListOrders::class)->name('dashboard.orders');
        Route::livewire('/orders/create', EditOrder::class)->name('dashboard.orders.create');
        Route::livewire('/orders/{order}', ViewOrder::class)->name('dashboard.orders.view');
        Route::livewire('/orders/{order}/edit', EditOrder::class)->name('dashboard.orders.edit');

        // Invoices
        Route::livewire('/invoices', ListInvoices::class)->name('dashboard.invoices');
        Route::livewire('/invoices/create', EditInvoice::class)->name('dashboard.invoices.create');
        Route::livewire('/invoices/{invoice}', ViewInvoice::class)->name('dashboard.invoices.view');
        Route::livewire('/invoices/{invoice}/edit', EditInvoice::class)->name('dashboard.invoices.edit');

        // Shipments
        Route::livewire('/shipments', ListShipments::class)->name('dashboard.shipments');

        // Products
        Route::livewire('/products', ListProducts::class)->name('dashboard.products');
        Route::livewire('/products/create', EditProduct::class)->name('dashboard.products.create');
        Route::livewire('/products/{product}', ViewProduct::class)->name('dashboard.products.view');
        Route::livewire('/products/{product}/edit', EditProduct::class)->name('dashboard.products.edit');

        // Product Categories
        Route::livewire('/product-categories', ListProductCategories::class)->name('dashboard.product-categories');
        Route::livewire('/product-categories/create', EditProductCategory::class)->name('dashboard.product-categories.create');
        Route::livewire('/product-categories/{productCategory}/edit', EditProductCategory::class)->name('dashboard.product-categories.edit');

        // Discounts
        Route::livewire('/discounts', ListDiscounts::class)->name('dashboard.discounts');
        Route::livewire('/discounts/create', EditDiscount::class)->name('dashboard.discounts.create');
        Route::livewire('/discounts/{discount}', ViewDiscount::class)->name('dashboard.discounts.view');
        Route::livewire('/discounts/{discount}/edit', EditDiscount::class)->name('dashboard.discounts.edit');

        // Tasks
        Route::livewire('/tasks', ListTasks::class)->name('dashboard.tasks');
        Route::livewire('/tasks/create', EditTask::class)->name('dashboard.tasks.create');
        Route::livewire('/tasks/{task}', ViewTask::class)->name('dashboard.tasks.view');
        Route::livewire('/tasks/{task}/edit', EditTask::class)->name('dashboard.tasks.edit');

        // Complaints
        Route::livewire('/complaints', ListComplaints::class)->name('dashboard.complaints');
        Route::livewire('/complaints/create', EditComplaint::class)->name('dashboard.complaints.create');
        Route::livewire('/complaints/{complaint}', ViewComplaint::class)->name('dashboard.complaints.view');
        Route::livewire('/complaints/{complaint}/edit', EditComplaint::class)->name('dashboard.complaints.edit');

        // Interactions
        Route::livewire('/interactions', ListInteractions::class)->name('dashboard.interactions');
        Route::livewire('/interactions/create', EditInteraction::class)->name('dashboard.interactions.create');
        Route::livewire('/interactions/{interaction}', ViewInteraction::class)->name('dashboard.interactions.view');
        Route::livewire('/interactions/{interaction}/edit', EditInteraction::class)->name('dashboard.interactions.edit');

        // Chat Sessions
        Route::livewire('/chat-sessions', ListChatSessions::class)->name('dashboard.chat-sessions');

        // Campaigns
        Route::livewire('/campaigns', ListCampaigns::class)->name('dashboard.campaigns');
        Route::livewire('/campaigns/{campaign}', ViewCampaign::class)->name('dashboard.campaigns.view');
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

// Public quote viewing route (no auth required)
Route::get('/quotes/view/{token}', QuoteViewController::class)->name('quotes.public-view');

// Complaint submission route (public - no team required)
Route::livewire('/complaints/submit', ComplaintSubmission::class)->name('complaints.submit');
