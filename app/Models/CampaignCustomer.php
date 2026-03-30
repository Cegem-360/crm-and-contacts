<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Fillable(['campaign_id', 'customer_id', 'added_at', 'added_by', 'notes'])]
final class CampaignCustomer extends Pivot
{
    protected $table = 'campaign_customer';

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    protected function casts(): array
    {
        return [
            'added_at' => 'datetime',
        ];
    }
}
