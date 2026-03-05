<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum CustomerType: string implements HasLabel
{
    case Individual = 'individual';
    case Company = 'company';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Individual => __('Individual'),
            self::Company => __('Company'),
        };
    }
}
