<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;

interface SalesIntegrationInterface
{
    /**
     * Push an order to the accounting system.
     *
     * @return array{success: bool, reference_id: string|null, message: string}
     */
    public function pushOrderToAccounting(Order $order): array;

    /**
     * Push an invoice to the finance system.
     *
     * @return array{success: bool, reference_id: string|null, message: string}
     */
    public function pushInvoiceToFinance(Invoice $invoice): array;

    /**
     * Check inventory availability for a product.
     *
     * @return array{available: bool, quantity: int, warehouse: string|null}
     */
    public function checkInventory(Product $product, int $quantity = 1): array;

    /**
     * Reserve stock for an order.
     *
     * @param  array<int, array{product_id: int, quantity: int}>  $items
     * @return array{success: bool, reservation_id: string|null, message: string}
     */
    public function reserveStock(Order $order, array $items): array;
}
