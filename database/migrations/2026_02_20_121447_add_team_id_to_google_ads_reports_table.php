<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['google_ads_reports', 'campaign_conversions', 'email_templates'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'team_id')) {
                Schema::table($table, function (Blueprint $blueprint): void {
                    $blueprint->foreignId('team_id')->nullable()->after('id')->constrained()->nullOnDelete();
                });
            }
        }

        // Populate team_id from the related campaign's team_id
        foreach (['google_ads_reports', 'campaign_conversions'] as $table) {
            DB::table($table)
                ->whereNull('team_id')
                ->update([
                    'team_id' => DB::raw("(SELECT campaigns.team_id FROM campaigns WHERE campaigns.id = {$table}.campaign_id)"),
                ]);
        }
    }

    public function down(): void
    {
        $tables = ['google_ads_reports', 'campaign_conversions', 'email_templates'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'team_id')) {
                Schema::table($table, function (Blueprint $blueprint): void {
                    $blueprint->dropConstrainedForeignId('team_id');
                });
            }
        }
    }
};
