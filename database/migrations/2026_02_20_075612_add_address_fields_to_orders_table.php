<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('shipping_address_id')->nullable()->constrained('customer_addresses')->nullOnDelete();
            $table->foreignId('billing_address_id')->nullable()->constrained('customer_addresses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shipping_address_id');
            $table->dropConstrainedForeignId('billing_address_id');
        });
    }
};
