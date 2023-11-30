<?php

namespace App\Filament\Resources\ERKCReadingResource\Pages;

use App\Filament\Resources\ERKCReadingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditERKCReading extends EditRecord
{
    protected static string $resource = ERKCReadingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
