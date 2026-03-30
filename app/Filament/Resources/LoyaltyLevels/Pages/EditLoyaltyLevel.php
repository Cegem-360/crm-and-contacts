<?php

declare(strict_types=1);

namespace App\Filament\Resources\LoyaltyLevels\Pages;

use App\Filament\Resources\LoyaltyLevels\LoyaltyLevelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditLoyaltyLevel extends EditRecord
{
    protected static string $resource = LoyaltyLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
