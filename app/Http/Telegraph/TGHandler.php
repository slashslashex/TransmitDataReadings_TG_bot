<?php


namespace App\Http\Telegraph;


use App\Http\Telegraph\ReadingHandler;
use App\Models\DVECElectricityReading;
use App\Models\DVECHotWaterReading;
use App\Models\ERKCReading;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Stringable;

class TGHandler extends WebhookHandler
{


    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->reply('Неизвестная команда');
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $this->reply('Используйте доступные команды');
    }

    public function start()
    {
        Telegraph::message('Выберите тип услуги')->keyboard(
            Keyboard::make()->buttons([
                Button::make('ДВЕЦ электроэнергия')->action('dvec_electricity'),
                Button::make('ДВЕЦ горячая вода')->action('dvec_hot'),
                Button::make('ЕРКЦ холодная и горячая вода')->action('erkc'),
                Button::make('Узнать переданные показания')->action('readings')
            ])
        )->send();
    }

    public function test()
    {
        $this->reply('3333');
    }

    public function dvec_electricity()
    {
        $currentDate = date('d');
        if ($currentDate >= 20
            && $currentDate <= 25)
        {
            Telegraph::message('Для передачи электроэнергии введите команду в формате "/de первые_5_цифр_счетчика", например "/de 04619"')->send();
        }
        else
        {
            Telegraph::message('Услуга доступна только между 20 и 25 числом каждого месяца')->send();
        }
    }

    public function dvec_hot()
    {
        $currentDate = date('d');
        if ($currentDate >= 20
            && $currentDate <= 25)
        {
            Telegraph::message('Для передачи горячей воды введите команду в формате "/dh первые_8_цифр_счетчика", например "/dh 00231207"')->send();
        }
        else
        {
            Telegraph::message('Услуга доступна только между 20 и 25 числом каждого месяца')->send();
        }
    }

    public function erkc()
    {
        $currentDate = date('d');
        if ($currentDate >= 20
            && $currentDate <= 25)
        {
            Telegraph::message('Для передачи показаний ХВГ и ГВС введите команду в формате "/er ХВС-ГВС", например "/er 00277173-00251063"')->send();
        }
        else
        {
            Telegraph::message('Услуга доступна только между 20 и 25 числом каждого месяца')->send();
        }
    }

    public function readings()
    {
        Telegraph::message('Чтобы узнать переданные показания за месяц введите команду в формате "/r месяц-год", например "/r январь-2023" или "/r 01-2023"')->send();
    }

    public function de($electricity)
    {
        $currentDate = date('d');
        if ($currentDate >= 20
            && $currentDate <= 25)
        {
            if (is_numeric($electricity)
                && strlen($electricity)===5)
            {
                $dvecElectricity = new DVECElectricityReading();
                $dvecElectricity->setAttribute('previous readings', 8888);
                $dvecElectricity->setAttribute('new readings', $electricity);
                $dvecElectricity->difference = $dvecElectricity['new readings'] - $dvecElectricity['previous readings'];
                $dvecElectricity->save();
                $this->reply('Данные записаны');
            }
            elseif ($electricity === '/de') {
                $this->reply('Вы не ввели показания');
            }
            elseif (strlen($electricity)!==5)
            {
                $this->reply('Должно быть 5 цифр');
            }
            else
            {
                $this->reply("Показания должны быть цифрами");
            }
        }
        else
        {
            $this->reply('Услуга доступна только между 20 и 25 числом каждого месяца');
            die();
        }
    }

    public function dh($hotWater)
    {
        $currentDate = date('d');
        if ($currentDate >= 20
            && $currentDate <= 25)
        {
            if (is_numeric($hotWater)
                && strlen($hotWater)===8)
            {
                $dvecHotWater = new DVECHotWaterReading();
                $dvecHotWater->setAttribute('previous readings', 8888);
                $dvecHotWater->setAttribute('new readings', $hotWater);
                $dvecHotWater->difference = $dvecHotWater['new readings'] - $dvecHotWater['previous readings'];
                $dvecHotWater->save();
                $this->reply('Данные записаны');
            }
            elseif ($hotWater === '/de') {
                $this->reply('Вы не ввели показания');
            }
            elseif (strlen($hotWater)!==8)
            {
                $this->reply('Должно быть 8 цифр');
            }
            else
            {
                $this->reply("Показания должны быть цифрами");
            }
        }
        else
        {
            $this->reply('Услуга доступна только между 20 и 25 числом каждого месяца');
            die();
        }
    }

    public function er($readings)
    {
        $currentDate = date('d');
        if ($currentDate >= 20
            && $currentDate <= 25)
        {
            $parts = explode('-', $readings);
            $hvs = $parts[0] ?? '';
            $gvs = $parts[1] ?? '';

            if (is_numeric($hvs)
                && is_numeric($gvs)
                && strlen($hvs)===8
                && strlen($gvs)===8)
            {
                $erkc = new ERKCReading();
                $erkc->setAttribute('hot water previous readings', 1);
                $erkc->setAttribute('hot water new readings', $gvs);
                $erkc->setAttribute('cold water previous readings', 2);
                $erkc->setAttribute('cold water new readings', $hvs);
                $erkc->$erkc['hot water difference'] = $erkc['hot water new readings'] - $erkc['hot water previous readings'];
                $erkc->$erkc['cold water difference'] = $erkc['cold water new readings'] - $erkc['cold water previous readings'];
                $erkc->save();
                $this->reply('Данные записаны');
            }
            elseif ($readings === '/er')
            {
                $this->reply("Вы не ввели показания");
            }
            elseif (strlen($hvs)!==8)
            {
                $this->reply('В показаниях ХВС (до тире) должно быть 8 цифр');
            }
            elseif (strlen($gvs)!==8)
            {
                $this->reply('В показаниях ГВС (после тире) должно быть 8 цифр');
            }
            else
            {
                $this->reply("Показания должны быть цифрами");
            }
        }
        else
        {
            $this->reply('Услуга доступна только между 20 и 25 числом каждого месяца');
            die();
        }
    }

    public function r($date)
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
            && $m>=01
            && $m <=12
            && (!empty($year)))
        {
            $month = $m;
        }
        elseif ($m==='/r')
        {
            $this->reply('Вы не ввели дату');
            die();
        }
        elseif (!is_numeric($m)
            && (!array_key_exists($m, $months)))
        {
            $this->reply("Неправильное название месяца");
            die();
        }
        elseif (is_numeric($m)
            && ($m<01 || $m>12))
        {
            $this->reply('Неправильный номер месяца');
            die();
        }
        elseif (empty($year))
        {
            $this->reply('Вы не ввели год');
            die();
        }
        elseif (!is_numeric($year) || strlen($year)!==4)
        {
            $this->reply('Год должен состоять из 4-х цифр');
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
            $dvecElectr = DVECElectricityReading::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->pluck('new readings');

            $dvecHotWater = DVECHotWaterReading::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->pluck('new readings');

            $erkcCold = ERKCReading::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->pluck('cold water new readings');

            $erkcHot = ERKCReading::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->pluck('hot water new readings');

            $this->reply("Ваши показания в выбранную дату:
            в ДВЕЦ:
            электроэнергия - $dvecElectr,
            ГВС - $dvecHotWater.
            в ЕРКЦ:
            ХВС - $erkcCold,
            ГВС - $erkcHot.");
        }
        else
        {
            $this->reply("Debug message lol [$month] [$year]");
        }
    }


}
