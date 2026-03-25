<?php

declare(strict_types=1);

use App\Enums\CommunicationChannel;
use App\Enums\CommunicationDirection;
use App\Enums\CommunicationStatus;
use App\Models\Customer;
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
        Schema::create('communications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Customer::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('channel')->default(CommunicationChannel::Email->value);
            $table->string('direction')->default(CommunicationDirection::Outbound->value);
            $table->string('subject')->nullable();
            $table->text('content');
            $table->string('message_id')->nullable();
            $table->string('in_reply_to')->nullable();
            $table->string('thread_id')->nullable();
            $table->string('from_email')->nullable();
            $table->string('to_email')->nullable();
            $table->json('cc')->nullable();
            $table->boolean('has_attachments')->default(false);
            $table->string('status')->default(CommunicationStatus::Pending->value);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index('message_id');
            $table->index('thread_id');
            $table->index('from_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communications');
    }
};
