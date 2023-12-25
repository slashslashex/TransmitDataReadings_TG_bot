<?php

namespace App\Filament\Resources\DVECElectricityReadingResource\Pages;

use App\Filament\Resources\DVECElectricityReadingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDVECElectricityReading extends EditRecord
{
    protected static string $resource = DVECElectricityReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
