<?php

namespace App\Exports;

use App\Models\DVECElectricityReading;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportDVECElectricityReading implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DVECElectricityReading::select(
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
            'Использовано кВт',
            'Комментарий'
        ];
    }

    public function handleExportElectr($chat)
    {
        Excel::store(new ExportDVECElectricityReading(), 'exportDvecElectricity.xlsx');
        $chat->document(Storage::path('exportDvecElectricity.xlsx'))->send();
    }
}
