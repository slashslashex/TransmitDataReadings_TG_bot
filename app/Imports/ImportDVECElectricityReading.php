<?php

namespace App\Imports;

use App\Models\DVECElectricityReading;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportDVECElectricityReading implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
//        $date = strtotime('1900-01-01');
//        $daysToAdd = $row[0]-2;
//        $newDate = strtotime("+$daysToAdd days", $date);
//        $result = date('Y-m-d', $newDate);

//        $date = DateTime::createFromFormat('d.m.Y', $row[0]);
//        $result = $date->format('Y-m-d');

        $format = strtotime($row[0]);
        $date = date('Y-m-d', $format);

//        $diff = $row[2] - $row[1];

        return new DVECElectricityReading([
            'transmit_date' => $date,
            'previous_readings' => $row[1],
            'new_readings' => $row[2],
            'difference' => $row[3],
            'comment' => $row[4]
        ]);
    }
}
