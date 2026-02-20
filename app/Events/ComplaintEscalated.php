<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Complaint;
use App\Models\ComplaintEscalation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final readonly class ComplaintEscalated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Complaint $complaint,
        public ComplaintEscalation $escalation,
    ) {}
}
