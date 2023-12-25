<?php

namespace App\Exports;

use App\Models\ERKCReading;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportERKCReading implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ERKCReading::select(
            'transmit_date',
            'cold_water_previous_readings',
            'cold_water_new_readings',
            'hot_water_previous_readings',
            'hot_water_new_readings',
            'cold_water_difference',
            'hot_water_difference',
            'comment'
            )->get();
    }

    public function headings(): array
    {
        return [
            'Дата передачи',
            'ХВС предыдущие показания',
            'ХВС переданные показания',
            'ГВС предыдущие показания',
            'ГВС предыдущие показания',
            'Использовано ХВС',
            'Использовано ГВС',
            'Комментарий'
        ];
    }

    public function handleExportErkc($chat)
    {
        Excel::store(new ExportERKCReading(), 'exportErkc.xlsx');
        $chat->document(Storage::path('exportErkc.xlsx'))->send();
    }
}
