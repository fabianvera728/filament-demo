<?php

namespace App\Filament\Admin\Resources\BonusResource\Pages;

use App\Filament\Admin\Resources\BonusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBonuses extends ListRecords
{
    protected static string $resource = BonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
