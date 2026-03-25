<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Quote;
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
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Quote::class)->nullable()->constrained()->nullOnDelete();
            $table->string('order_number');
            $table->date('order_date');
            $table->string('status')->default(OrderStatus::Pending->value);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->nullable()->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->foreignId('shipping_address_id')->nullable()->constrained('customer_addresses')->nullOnDelete();
            $table->foreignId('billing_address_id')->nullable()->constrained('customer_addresses')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['team_id', 'order_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
