<?php

namespace App\Filament\Ruler\Resources\RulerResource\Pages;

use App\Filament\Ruler\Resources\RulerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRuler extends EditRecord
{
    protected static string $resource = RulerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
