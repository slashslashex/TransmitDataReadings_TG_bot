<?php

namespace App\Filament\Resources\DVECReadingResource\Pages;

use App\Filament\Resources\DVECReadingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDVECReading extends EditRecord
{
    protected static string $resource = DVECReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
