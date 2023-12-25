<?php

namespace App\Filament\Resources\DVECHotWaterReadingResource\Pages;

use App\Filament\Resources\DVECHotWaterReadingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDVECHotWaterReadings extends ListRecords
{
    protected static string $resource = DVECHotWaterReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
