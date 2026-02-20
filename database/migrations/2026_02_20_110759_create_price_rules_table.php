<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_rules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('rule_type');
            $table->json('conditions')->nullable();
            $table->string('discount_type');
            $table->decimal('discount_value', 10, 2);
            $table->integer('priority')->default(0);
            $table->boolean('combinable')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['team_id', 'rule_type', 'is_active']);
            $table->index(['customer_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_rules');
    }
};
