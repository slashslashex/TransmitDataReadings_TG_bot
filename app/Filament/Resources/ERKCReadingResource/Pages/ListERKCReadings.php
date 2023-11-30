<?php

namespace App\Filament\Resources\ERKCReadingResource\Pages;

use App\Filament\Resources\ERKCReadingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListERKCReadings extends ListRecords
{
    protected static string $resource = ERKCReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
