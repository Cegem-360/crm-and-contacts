<?php

declare(strict_types=1);

namespace App\Filament\Resources\LoyaltyLevels\Pages;

use App\Filament\Resources\LoyaltyLevels\LoyaltyLevelResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateLoyaltyLevel extends CreateRecord
{
    protected static string $resource = LoyaltyLevelResource::class;
}
