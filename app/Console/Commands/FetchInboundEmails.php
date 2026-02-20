<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Team;
use App\Services\EmailIntegrationService;
use Illuminate\Console\Command;

final class FetchInboundEmails extends Command
{
    protected $signature = 'emails:fetch {--team= : Specific team ID to fetch for} {--account=default : IMAP account name}';

    protected $description = 'Fetch inbound emails from IMAP and store as communications';

    public function handle(EmailIntegrationService $service): int
    {
        $teamId = $this->option('team');
        $account = (string) $this->option('account');

        if ($teamId) {
            $teams = Team::query()->where('id', $teamId)->get();
        } else {
            $teams = Team::all();
        }

        $totalFetched = 0;

        foreach ($teams as $team) {
            $this->info("Fetching emails for team: {$team->name}");
            $emails = $service->fetchInboundEmails($team->id, $account);
            $totalFetched += $emails->count();
            $this->info("  Fetched {$emails->count()} new emails.");
        }

        $this->info("Total emails fetched: {$totalFetched}");

        return self::SUCCESS;
    }
}
