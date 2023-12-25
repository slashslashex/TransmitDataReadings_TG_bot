<?php

namespace App\Models;

use App\DateChecker;
use App\Http\Telegraph\WebHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DVECElectricityReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'previous_readings',
        'new_readings',
        'difference',
        'transmit_date',
        'comment'
    ];

    public function testHandler($chat)
    {
        $checkDate = new DateChecker();
        if ($checkDate->checkDate()) {
            $chat->message('2222222222')->send();
        } else $chat->message('test ok')->send();
    }


    public function buttonElectricity($chat): void
    {
        $checkDate = new DateChecker();
        if ($checkDate->checkDate()) {
            $chat->message('Для передачи электроэнергии введите команду в формате "/de первые_5_цифр_счетчика", например "/de 04619"')->send();
        } else {
            $chat->message('Услуга доступна только с 20 по 25 число каждого месяца')->send();
        }
    }

    public function handleDataPost($electricity, $chat): void
    {
        $checkDate = new DateChecker();
        if ($checkDate->checkDate()) {
            if (is_numeric($electricity)
                && strlen($electricity) === 5) {

                $client = new WebHandler();

                $url = 'https://www.dvec.ru/khabsbyt/private_clients/pokazaniya/index.php';

                // Получаем HTML-код страницы
                $html = $client->getHtml($url);

                // Извлекаем все скрытые поля
                $hiddenFields = $client->extractHiddenFields($html);

                // Определяем данные для отправки
                $data = [
                    'branch' => 'hes',
                    'account' => '030111840605200600',
                    'meter' => '011067158232208',

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
//                     Извлекаем значения атрибута value из элементов input[readonly=""]
                    $previousReadings = $client->extractReadonlyValues($firstResponse);

                    $data = [
                        'reading1' => $electricity,
                    ];

                    $hiddenFields = $client->extractHiddenFields($firstResponse);
                    foreach ($hiddenFields as $field) {
                        $data[$field['name']] = $field['value'];
                    }

//                     Отправляем POST-запрос с новыми данными
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

                        $dvecElectricity = new DVECElectricityReading();
                        $dvecElectricity->setAttribute('transmit_date', date('Y-m-d'));
                        $dvecElectricity->setAttribute('previous_readings', $previousReadings[0]);
                        $dvecElectricity->setAttribute('new_readings', $electricity);
                        $dvecElectricity->difference = $dvecElectricity['new_readings'] - $previousReadings[0];
                        $dvecElectricity->save();
                        $chat->message('Данные успешно переданы и записаны')->send();

                    }
                }
            } elseif ($electricity === '/de') {
                $chat->message('Вы не ввели показания')->send();
            } elseif (strlen($electricity) !== 5) {
                $chat->message('Должно быть 5 цифр')->send();
            } else {
                $chat->message("Показания должны быть цифрами")->send();
            }
        } else {
            $chat->message('Услуга доступна только с 20 по 25 число каждого месяца')->send();
            die();
        }
    }

}
