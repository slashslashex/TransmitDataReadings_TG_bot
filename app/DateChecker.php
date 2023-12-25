<?php
namespace App;

class DateChecker
{

    public function checkDate(): bool
    {
        $currentDate = date('d');
        if ($currentDate >= 20 && $currentDate <= 25) {
            return true;
        } else {
            return false;
        }
    }

}
