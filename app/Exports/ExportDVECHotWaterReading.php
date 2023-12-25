<?php

namespace App\Exports;

use App\Models\DVECHotWaterReading;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportDVECHotWaterReading implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DVECHotWaterReading::select(
            'transmit_date',
            'previous_readings',
            'new_readings',
            'difference',
            'comment'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Дата передачи',
            'Предыдущие показания',
            'Переданные показания',
            'Использовано',
            'Комментарий'
        ];
    }

    public function handleExportHotWater($chat)
    {
        Excel::store(new ExportDVECHotWaterReading(), 'exportDvecHotWater.xlsx');
        $chat->document(Storage::path('exportDvecHotWater.xlsx'))->send();
    }
}
