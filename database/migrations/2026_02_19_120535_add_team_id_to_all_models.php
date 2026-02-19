<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'customers',
            'customer_contacts',
            'customer_addresses',
            'customer_attributes',
            'opportunities',
            'quotes',
            'quote_items',
            'orders',
            'order_items',
            'invoices',
            'products',
            'product_categories',
            'discounts',
            'campaigns',
            'campaign_responses',
            'complaints',
            'complaint_escalations',
            'interactions',
            'tasks',
            'chat_sessions',
            'chat_messages',
            'communications',
            'bug_reports',
            'shipments',
            'shipment_items',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'team_id')) {
                Schema::table($table, function (Blueprint $blueprint): void {
                    $blueprint->foreignId('team_id')->nullable()->after('id')->constrained()->nullOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'customers',
            'customer_contacts',
            'customer_addresses',
            'customer_attributes',
            'opportunities',
            'quotes',
            'quote_items',
            'orders',
            'order_items',
            'invoices',
            'products',
            'product_categories',
            'discounts',
            'campaigns',
            'campaign_responses',
            'complaints',
            'complaint_escalations',
            'interactions',
            'tasks',
            'chat_sessions',
            'chat_messages',
            'communications',
            'bug_reports',
            'shipments',
            'shipment_items',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'team_id')) {
                Schema::table($table, function (Blueprint $blueprint): void {
                    $blueprint->dropConstrainedForeignId('team_id');
                });
            }
        }
    }
};
