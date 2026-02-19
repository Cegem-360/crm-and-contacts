<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTeam;
use Database\Factories\CustomerAttributeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CustomerAttribute extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<CustomerAttributeFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'customer_id',
        'attribute_key',
        'attribute_value',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
