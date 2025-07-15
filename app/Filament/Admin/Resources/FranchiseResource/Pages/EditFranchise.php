<?php

namespace App\Filament\Admin\Resources\FranchiseResource\Pages;

use App\Filament\Admin\Resources\FranchiseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFranchise extends EditRecord
{
    protected static string $resource = FranchiseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
