<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('communications', function (Blueprint $table): void {
            $table->string('message_id')->nullable()->after('content');
            $table->string('in_reply_to')->nullable()->after('message_id');
            $table->string('thread_id')->nullable()->after('in_reply_to');
            $table->string('from_email')->nullable()->after('thread_id');
            $table->string('to_email')->nullable()->after('from_email');
            $table->json('cc')->nullable()->after('to_email');
            $table->boolean('has_attachments')->default(false)->after('cc');

            $table->index('message_id');
            $table->index('thread_id');
            $table->index('from_email');
        });
    }

    public function down(): void
    {
        Schema::table('communications', function (Blueprint $table): void {
            $table->dropIndex(['message_id']);
            $table->dropIndex(['thread_id']);
            $table->dropIndex(['from_email']);
            $table->dropColumn([
                'message_id', 'in_reply_to', 'thread_id',
                'from_email', 'to_email', 'cc', 'has_attachments',
            ]);
        });
    }
};
