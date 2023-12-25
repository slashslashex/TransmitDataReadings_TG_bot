<?php

namespace App\Models;

class DBRead
{

    public function buttonDBRead($chat): void
    {
        $chat->message('Чтобы узнать переданные показания за месяц введите команду в формате "/r месяц-год", например "/r январь-2023" или "/r 01-2023"')->send();
    }

    public function handleDataRead($date, $chat): void
    {
        $parts = explode('-', $date);
        $m = $parts[0] ?? '';
        $year = $parts[1] ?? '';

        $months = [
            'январь' => '01',
            'февраль' => '02',
            'март' => '03',
            'апрель' => '04',
            'май' => '05',
            'июнь' => '06',
            'июль' => '07',
            'август' => '08',
            'сентябрь' => '09',
            'октябрь' => '10',
            'ноябрь' => '11',
            'декабрь' => '12'
        ];

        if (is_numeric($m)
            && is_numeric($year)
            && strlen($m)===2
            && strlen($year)===4
            && $m>=01
            && $m <=12
            && (!empty($year)))
        {
            $month = $m;
        }
        elseif ($m==='/r')
        {
            $chat->message('Вы не ввели дату')->send();
            die();
        }
        elseif (!is_numeric($m)
            && (!array_key_exists($m, $months)))
        {
            $chat->message("Неправильное название месяца")->send();
            die();
        }
        elseif (is_numeric($m)
            && strlen($m)!==2)
        {
            $chat->message('Неправильный формат номера месяца')->send();
            die();
        }
        elseif (is_numeric($m)
            && ($m<01 || $m>12))
        {
            $chat->message('Неправильный номер месяца')->send();
            die();
        }
        elseif (empty($year))
        {
            $chat->message('Вы не ввели год')->send();
            die();
        }
        elseif (!is_numeric($year)
            || strlen($year)!==4)
        {
            $chat->message('Год должен состоять из 4-х цифр')->send();
            die();
        }
        else $month = $months[$m];

        if (is_numeric($month)
            && strlen($month)===2
            && $month >=01
            && $month <=12
            && is_numeric($year)
            && strlen($year)===4)
        {

            $dvecElectr = DVECElectricityReading::whereYear('transmit_date', $year)
                ->whereMonth('transmit_date', $month)
                ->pluck('new_readings');

            $dvecHotWater = DVECHotWaterReading::whereYear('transmit_date', $year)
                ->whereMonth('transmit_date', $month)
                ->pluck('new_readings');

            $erkcCold = ERKCReading::whereYear('transmit_date', $year)
                ->whereMonth('transmit_date', $month)
                ->pluck('cold_water_new_readings');

            $erkcHot = ERKCReading::whereYear('transmit_date', $year)
                ->whereMonth('transmit_date', $month)
                ->pluck('hot_water_new_readings');

            $chat->html("Ваши показания на $parts[0] $parts[1]:
            в ДВЕЦ:
            электроэнергия - $dvecElectr,
            ГВС - $dvecHotWater.
            в ЕРКЦ:
            ХВС - $erkcCold,
            ГВС - $erkcHot.")->send();
        }
        else
        {
            $chat->message("Debug message lol [$month] [$year]")->send();
        }
    }
}
