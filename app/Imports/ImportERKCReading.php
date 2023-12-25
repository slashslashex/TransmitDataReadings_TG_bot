<?php

namespace App\Imports;

use App\Models\ERKCReading;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportERKCReading implements ToModel
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

//        $diff = $row[2] - $row[1];

        return new ERKCReading([
            'transmit_date' => $date,
            'cold_water_previous_readings' => $row[1],
            'cold_water_new_readings' => $row[2],
            'hot_water_previous_readings' => $row[3],
            'hot_water_new_readings' => $row[4],
            'cold_water_difference' => $row[5],
            'hot_water_difference' => $row[6],
            'comment' => $row[7]
        ]);
    }
}
