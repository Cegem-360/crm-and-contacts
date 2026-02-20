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
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('complaint_number')->unique()->nullable()->after('id');
            $table->string('type')->nullable()->after('assigned_to');
            $table->string('subject')->nullable()->after('type');
            $table->timestamp('sla_deadline_at')->nullable()->after('resolved_at');
            $table->unsignedInteger('escalation_level')->default(0)->after('sla_deadline_at');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['complaint_number', 'type', 'subject', 'sla_deadline_at', 'escalation_level']);
        });
    }
};
