<?php

namespace App\Filament\Resources\DVECReadingResource\Pages;

use App\Filament\Resources\DVECReadingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDVECReadings extends ListRecords
{
    protected static string $resource = DVECReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
