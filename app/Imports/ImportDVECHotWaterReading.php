<?php

namespace App\Imports;

use App\Models\DVECHotWaterReading;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportDVECHotWaterReading implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
//        $date = strtotime('1900-01-01');
//        $daysToAdd = $row[3]-2;
//        $newDate = strtotime("+$daysToAdd days", $date);
//        $result = date('Y-m-d', $newDate);

        $format = strtotime($row[0]);
        $date = date('Y-m-d', $format);

//        $decimal = str_replace(',', '.', $row[3]);

//        $diff = $row[2] - $row[1];

        return new DVECHotWaterReading([
            'transmit_date' => $date,
            'previous_readings' => $row[1],
            'new_readings' => $row[2],
            'difference' => $row[3],
            'comment' => $row[4]
        ]);
    }
}
