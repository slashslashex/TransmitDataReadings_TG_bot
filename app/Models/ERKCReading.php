<?php

namespace App\Models;

use App\DateChecker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ERKCReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'cold_water_previous_readings',
        'cold_water_new_readings',
        'hot_water_previous_readings',
        'hot_water_new_readings',
        'hot_water_difference',
        'cold_water_difference',
        'transmit_date',
        'comment'
    ];

    public function buttonErkc($chat): void
    {
        $checkDate = new DateChecker();
        if ($checkDate->checkDate())
        {
            $chat->message('Для передачи показаний ХВГ и ГВС введите команду в формате "/er ХВС-ГВС", например "/er 00277173-00251063"')->send();
        }
        else
        {
            $chat->message('Услуга доступна только с 20 по 25 число каждого месяца')->send();
        }
    }

    public function handleDataPost($readings, $chat): void
    {
        $checkDate = new DateChecker();
        if ($checkDate->checkDate())
        {
            $parts = explode('-', $readings);
            $hvs = $parts[0] ?? '';
            $gvs = $parts[1] ?? '';

            if (is_numeric($hvs)
                && is_numeric($gvs)
                && strlen($hvs)===8
                && strlen($gvs)===8)
            {

                // Сохраняем данные в файл JSON
                $data = json_encode(['hvs' => $hvs, 'gvs' => $gvs]);

                file_put_contents(base_path('public/dataToPuppeteer.json'), $data);

                //Выполняем puppeteer

                $scriptPath = base_path('public/script.js');
                $dataToPhpPath = base_path('public/dataToPhp.json');
                $chat->message('Ждите')->send();


//              Выполнение скрипта JavaScript с помощью функции exec
                $result = exec("\"node\" \"$scriptPath\" 2>&1", $output, $returnCode);

                if ($returnCode !== 0) {
                    // Логируйте ошибку или вывод
                    Log::error("Error executing script: $result");
                    Log::error("Output: " . implode(PHP_EOL, $output));
                }
                sleep(5);

//                Читаем данные из файла
                $data = json_decode(file_get_contents($dataToPhpPath), true);

//                Обрабатываем данные в PHP
                $hvsPrev = $data['HVS_prev'];
                $gvsPrev = $data['GVS_prev'];

                $gvsForStore = substr_replace($gvs, '.', 5, 0);
                $hvsForStore = substr_replace($hvs, '.', 5, 0);
                $gvsPrevForStore = str_replace(',', '.', $gvsPrev);
                $hvsPrevForStore = str_replace(',', '.', $hvsPrev);

                $erkc = new ERKCReading();
                $erkc->setAttribute('transmit_date', date('Y-m-d'));

                $erkc->setAttribute('hot_water_previous_readings', $gvsPrevForStore);
                $chat->message("gvsPrevForStore $gvsPrevForStore")->send();

                $erkc->setAttribute('hot_water_new_readings', $gvsForStore);
                $chat->message("gvs $gvsForStore")->send();

                $erkc->setAttribute('cold_water_previous_readings', $hvsPrevForStore);
                $chat->message("hvsPrev $hvsPrevForStore")->send();

                $erkc->setAttribute('cold_water_new_readings', $hvsForStore);
                $chat->message("hvs $hvsForStore")->send();
                $erkc->hot_water_difference = $erkc['hot_water_new_readings'] - $erkc['hot_water_previous_readings'];
                $erkc->cold_water_difference = $erkc['cold_water_new_readings'] - $erkc['cold_water_previous_readings'];
                $erkc->save();
                $chat->message('Данные успешно переданы и записаны')->send();


//                $filesToDelete = [
//                    base_path('public/dataToPuppeteer.json'),
//                    base_path('public/dataToPhp.json'),
//                    // Добавьте пути к остальным файлам, которые вы хотите удалить
//                ];

//                foreach ($filesToDelete as $file) {
//                    // Проверяем, существует ли файл
//                    if (file_exists($file)) {
//                        // Пробуем удалить файл
//                        if (unlink($file)) {
//                            echo "Временные файлы удалены";
//                        } else {
//                            echo "Не удалось удалить временные файлы";
//                        }
//                    } else {
//                        echo "Временных файлов не обнаружено";
//                    }
//                }
            }
            elseif ($readings === '/er')
            {
                $chat->message("Вы не ввели показания")->send();
            }
            elseif (strlen($hvs)!==8)
            {
                $chat->message('В показаниях ХВС (до тире) должно быть 8 цифр')->send();
            }
            elseif (strlen($gvs)!==8)
            {
                $chat->message('В показаниях ГВС (после тире) должно быть 8 цифр')->send();
            }
            else
            {
                $chat->message("Показания должны быть цифрами")->send();
            }
        }
        else
        {
            $chat->message('Услуга доступна только с 20 по 25 число каждого месяца')->send();
            die();
        }
    }
}
