<?php

namespace App\Filament\Admin\Resources\FranchiseResource\Pages;

use App\Filament\Admin\Resources\FranchiseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFranchises extends ListRecords
{
    protected static string $resource = FranchiseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
