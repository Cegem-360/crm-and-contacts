<?php

declare(strict_types=1);

use App\Enums\InteractionType;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\EmailTemplate;
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
        Schema::create('interactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Customer::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(CustomerContact::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('type')->default(InteractionType::Note->value);
            $table->foreignIdFor(EmailTemplate::class)->nullable()->constrained()->nullOnDelete();
            $table->string('category')->default('general');
            $table->string('channel')->nullable();
            $table->string('direction')->nullable();
            $table->string('status')->default('completed');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->timestamp('interaction_date');
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->string('next_action')->nullable();
            $table->date('next_action_date')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->string('email_recipient')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
