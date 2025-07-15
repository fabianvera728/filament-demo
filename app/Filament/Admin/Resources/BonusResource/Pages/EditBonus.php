<?php

namespace App\Filament\Admin\Resources\BonusResource\Pages;

use App\Filament\Admin\Resources\BonusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBonus extends EditRecord
{
    protected static string $resource = BonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
