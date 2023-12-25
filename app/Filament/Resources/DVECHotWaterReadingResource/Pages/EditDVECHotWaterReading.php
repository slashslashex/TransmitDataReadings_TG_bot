<?php

namespace App\Filament\Resources\DVECHotWaterReadingResource\Pages;

use App\Filament\Resources\DVECHotWaterReadingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDVECHotWaterReading extends EditRecord
{
    protected static string $resource = DVECHotWaterReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
