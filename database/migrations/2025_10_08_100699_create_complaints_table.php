<?php

declare(strict_types=1);

use App\Enums\ComplaintSeverity;
use App\Enums\ComplaintStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
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
        Schema::create('complaints', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('complaint_number')->unique()->nullable();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Order::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'reported_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->nullable();
            $table->string('subject')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('severity')->default(ComplaintSeverity::Medium->value);
            $table->string('status')->default(ComplaintStatus::Open->value);
            $table->text('resolution')->nullable();
            $table->timestamp('reported_at');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('sla_deadline_at')->nullable();
            $table->unsignedInteger('escalation_level')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
