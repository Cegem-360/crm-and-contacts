<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ComplaintController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\GenerateInvoicePdfController;
use App\Http\Controllers\Api\V1\IntegrationController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ShipmentController;
use App\Http\Controllers\Api\V1\StoreShipmentTrackingEventController;
use App\Http\Controllers\Api\V1\TransitionOrderStatusController;
use App\Http\Controllers\Api\V1\UpdateShipmentStatusController;
use App\Http\Controllers\Api\V1\WebhookController;
use Illuminate\Support\Facades\Route;

// API V1 Routes
Route::prefix('v1')->name('api.v1.')->group(function (): void {
    // Authentication routes
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function (): void {
        // Auth routes
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [AuthController::class, 'me'])->name('me');

        // Customer routes
        Route::apiResource('customers', CustomerController::class);

        // Order routes
        Route::apiResource('orders', OrderController::class);
        Route::put('/orders/{order}/status', TransitionOrderStatusController::class)->name('orders.transitionStatus');

        // Invoice routes
        Route::apiResource('invoices', InvoiceController::class);
        Route::get('/invoices/{invoice}/pdf', GenerateInvoicePdfController::class)->name('invoices.pdf');

        // Complaint routes
        Route::apiResource('complaints', ComplaintController::class);

        // Shipment routes
        Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store');
        Route::get('/shipments/{trackingNumber}', [ShipmentController::class, 'show'])->name('shipments.show');
        Route::put('/shipments/{trackingNumber}/status', UpdateShipmentStatusController::class)->name('shipments.updateStatus');
        Route::post('/shipments/{trackingNumber}/tracking', StoreShipmentTrackingEventController::class)->name('shipments.storeTracking');

        // Integration routes
        Route::prefix('integration')->name('integration.')->group(function (): void {
            Route::post('/orders/{order}/push', [IntegrationController::class, 'pushOrder'])->name('orders.push');
            Route::post('/invoices/{invoice}/push', [IntegrationController::class, 'pushInvoice'])->name('invoices.push');
            Route::get('/products/{product}/inventory', [IntegrationController::class, 'checkInventory'])->name('products.inventory');
            Route::post('/orders/{order}/reserve-stock', [IntegrationController::class, 'reserveStock'])->name('orders.reserveStock');
        });
    });

    // Webhook routes (token-based auth, no Sanctum)
    Route::prefix('webhooks')->name('webhooks.')->group(function (): void {
        Route::post('/inventory-changed', [WebhookController::class, 'inventoryChanged'])->name('inventory-changed');
    });
});
