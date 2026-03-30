<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class ListModels implements Tool
{
    /**
     * @var array<string, array{model: class-string, description: string, relationships: list<string>}>
     */
    public const array AVAILABLE_MODELS = [
        'customers' => [
            'model' => \App\Models\Customer::class,
            'description' => 'CRM customers with contact info, loyalty data, and business details',
            'relationships' => ['contacts', 'addresses', 'orders', 'invoices', 'opportunities', 'complaints', 'loyaltyLevel'],
        ],
        'orders' => [
            'model' => \App\Models\Order::class,
            'description' => 'Sales orders linked to customers with items and shipping',
            'relationships' => ['customer', 'orderItems', 'invoices', 'shipments'],
        ],
        'invoices' => [
            'model' => \App\Models\Invoice::class,
            'description' => 'Invoices generated from orders with payment tracking',
            'relationships' => ['customer', 'order', 'invoiceItems'],
        ],
        'products' => [
            'model' => \App\Models\Product::class,
            'description' => 'Product catalog with pricing and categories',
            'relationships' => ['category'],
        ],
        'opportunities' => [
            'model' => \App\Models\Opportunity::class,
            'description' => 'Sales pipeline opportunities with stages and probability',
            'relationships' => ['customer', 'campaign', 'assignedUser', 'quotes'],
        ],
        'complaints' => [
            'model' => \App\Models\Complaint::class,
            'description' => 'Customer complaints and issue tracking',
            'relationships' => ['customer'],
        ],
        'campaigns' => [
            'model' => \App\Models\Campaign::class,
            'description' => 'Marketing campaigns targeting customers',
            'relationships' => ['customers'],
        ],
        'quotes' => [
            'model' => \App\Models\Quote::class,
            'description' => 'Sales quotes linked to opportunities and customers',
            'relationships' => ['customer', 'opportunity'],
        ],
        'shipments' => [
            'model' => \App\Models\Shipment::class,
            'description' => 'Shipment tracking for orders',
            'relationships' => ['order', 'customer', 'carrier'],
        ],
        'loyalty_levels' => [
            'model' => \App\Models\LoyaltyLevel::class,
            'description' => 'Loyalty program tiers and benefits',
            'relationships' => [],
        ],
        'interactions' => [
            'model' => \App\Models\Interaction::class,
            'description' => 'Customer interaction history (calls, meetings, emails)',
            'relationships' => ['customer'],
        ],
        'tasks' => [
            'model' => \App\Models\Task::class,
            'description' => 'Tasks assigned to team members',
            'relationships' => ['customer'],
        ],
    ];

    public function description(): Stringable|string
    {
        return 'List all available database models and their descriptions. Use this to understand what data is available before querying.';
    }

    public function handle(Request $request): Stringable|string
    {
        $result = [];

        foreach (self::AVAILABLE_MODELS as $key => $config) {
            $result[] = [
                'key' => $key,
                'description' => $config['description'],
                'available_relationships' => $config['relationships'],
            ];
        }

        return json_encode($result, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
