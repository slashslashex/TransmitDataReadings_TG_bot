<?php

namespace App\Models;

use App\DateChecker;
use App\Http\Telegraph\WebHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DVECHotWaterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'previous_readings',
        'new_readings',
        'difference',
        'transmit_date',
        'comment'
    ];

    public function buttonHotWater($chat): void
    {
        $checkDate = new DateChecker();
        if ($checkDate->checkDate())
        {
            $chat->message('Для передачи горячей воды введите команду в формате "/dh первые_8_цифр_счетчика", например "/dh 00231207"')->send();
        }
        else
        {
            $chat->message('Услуга доступна только с 20 по 25 число каждого месяца')->send();
        }
    }

    public function handleDataPost($hotWater, $chat): void
    {
        $checkDate = new DateChecker();
        if ($checkDate->checkDate())
        {
            if (is_numeric($hotWater)
                && strlen($hotWater)===8)

            {

                $client = new WebHandler();

                $url = 'https://www.dvec.ru/khabsbyt/private_clients/pokazaniya/gvs/index.php';

                // Получаем HTML-код страницы
                $html = $client->getHtml($url);

                // Извлекаем все скрытые поля
                $hiddenFields = $client->extractHiddenFields($html);

                // Определяем данные для отправки
                $data = [
                    'branch' => 'hes',
                    'account' => '01360567',
                    'meter' => '831294116',

                    // Добавьте другие поля и значения для отправки
                ];

                // Добавляем скрытые поля в массив $data
                foreach ($hiddenFields as $field) {
                    $data[$field['name']] = $field['value'];
                }

                // Отправляем POST-запрос с обновленными данными
                $firstResponse = $client->postFormData($url, $data);

                // Извлекаем сообщение из элемента div[id="alert"]
                $alertMessage = $client->extractAlertMessage($firstResponse);

                // Проверяем, есть ли сообщение об ошибке
                if ($alertMessage) {
                    $chat->message($alertMessage)->send();
                    die();
                } else {
                    // Извлекаем значения атрибута value из элементов input[readonly=""]
                    $previousReadings = $client->extractReadonlyValues($firstResponse);

                    $data = [
                        'reading1' => $hotWater,
                    ];

                    $hiddenFields = $client->extractHiddenFields($firstResponse);
                    foreach ($hiddenFields as $field) {
                        $data[$field['name']] = $field['value'];
                    }

                    // Отправляем POST-запрос с новыми данными
                    $secondResponse = $client->postFormData($url, $data);


                    $alertMessage = $client->extractAlertMessage($secondResponse);
                    if ($alertMessage) {
                        $chat->message($alertMessage)->send();
                        die();
                    } else {
                        $data = [];

                        $hiddenFields = $client->extractHiddenFields($secondResponse);
                        foreach ($hiddenFields as $field) {
                            if ($field['name'] === 'step') {
                                $data['step'] = '3';
                            } else {
                                $data[$field['name']] = $field['value'];
                            }
                        }

                        $thirdResponse = $client->postFormData($url, $data);
                        $successMessage = $client->extractSuccessMessage($thirdResponse);
                        $chat->message($successMessage)->send();
                        $hotWaterForStore = substr_replace($hotWater, '.', 5, 0);
                        $chat->message($hotWaterForStore)->send();

                        $dvecHotWater = new DVECHotWaterReading();
                        $dvecHotWater->setAttribute('transmit_date', date('Y-m-d'));
                        $dvecHotWater->setAttribute('previous_readings', $previousReadings[0]);
                        $dvecHotWater->setAttribute('new_readings', $hotWaterForStore);
                        $dvecHotWater->difference = $dvecHotWater['new_readings'] - $previousReadings[0];
                        $dvecHotWater->save();
                        $chat->message('Данные успешно переданы и записаны')->send();

                    }
                }
            }
            elseif ($hotWater === '/dh') {
                $chat->message('Вы не ввели показания')->send();
            }
            elseif (strlen($hotWater)!==8)
            {
                $chat->message('Должно быть 8 цифр')->send();
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
