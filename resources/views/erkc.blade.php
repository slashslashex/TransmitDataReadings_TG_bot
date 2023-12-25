<?php

namespace App\Http\Telegraph;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;

class WebHandler
{
    private $client;
    private $cookieJar;

    public function __construct()
    {
        // Создаем новый экземпляр Guzzle HTTP клиента
        $this->client = new Client([
            'verify' => false, // Отключить проверку SSL-сертификата
        ]);
        // Создаем новый cookie jar для хранения куки
        $this->cookieJar = new CookieJar();
    }

    /**
     * Получает HTML-код страницы по указанному URL.
     *
     * @param string $url URL страницы
     * @return string HTML-код страницы
     */
    public function getHtml($url)
    {
        $response = $this->client->get($url, [
            'cookies' => $this->cookieJar,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Отправляет POST-запрос с форм-данными на указанный URL.
     *
     * @param string $url URL для отправки запроса
     * @param array $data Данные формы
     * @return string Ответ на POST-запрос
     */
    public function postFormData($url, $data)
    {
        $response = $this->client->post($url, [
            'cookies' => $this->cookieJar,
            'form_params' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Извлекаем все скрытые поля из HTML-кода страницы.
     *
     * @param string $html HTML-код страницы
     * @return array Массив скрытых полей с атрибутами name и value
     */
    public function extractHiddenFields($html)
    {
        $crawler = new Crawler($html);
        $hiddenFields = $crawler->filter('input[type="hidden"]')->each(function (Crawler $node) {
            return [
                'name' => $node->attr('name'),
                'value' => $node->attr('value'),
            ];
        });

        return $hiddenFields;
    }

    /**
     * Извлекаем csrf-token из HTML-кода страницы.
     *
     * @param string $html HTML-код страницы
     * @return array Массив скрытых полей с атрибутами name и value
     */
    public function extractCsrf($html)
    {
        $crawler = new Crawler($html);

        $csrfTokens = $crawler->filter('meta[name="csrf-token-value"]')->each(function (Crawler $node) {
            return [
                'csrftoken' => $node->attr('content')
            ];
        });

        return $csrfTokens;
    }

    /**
     * Извлекаем сообщение из элемента div[id="alert"] на странице.
     *
     * @param string $html HTML-код страницы
     * @return string|null Сообщение или null, если элемент не найден
     */
    public function extractAlertMessage($html)
    {
        $crawler = new Crawler($html);
        $alertElement = $crawler->filter('div[id="alert"] span[class="msg"]');

        if ($alertElement->count() > 0) {
            return $alertElement->text();
        }

        return null;
    }

    /**
     * Извлекает предыдущие показания.
     *
     * @param string $html HTML-код страницы
     * @return array Массив значений атрибута value
     */
    public function extractReadonlyValues($html)
    {
        $crawler = new Crawler($html);
        $readonlyValues = $crawler->filter('input[readonly=""]')->extract(['value']);

        return $readonlyValues;
    }

    /**
     * Извлекает success message.
     *
     * @param string $html HTML-код страницы
     * @return string|null Сообщение или null, если элемент не найден
     */
    public function extractSuccessMessage($html)
    {
        $crawler = new Crawler($html);
        $successElement = $crawler->filter('div[class="wysiwyg"] h1');

        if ($successElement->count() > 0) {
            return $successElement->text();
        }
        return null;
    }
}
//показания к передаче
$newReadings = '00384194';
// Создаем экземпляр класса ReadingHandler
$client = new WebHandler();

// Определяем URL веб-сайта
$url = 'https://www.dvec.ru/khabsbyt/private_clients/pokazaniya/gvs/index.php';

// Получаем HTML-код страницы
$html = $client->getHtml($url);

// Извлекаем все скрытые поля
$hiddenFields = $client->extractHiddenFields($html);

//Извлекаем csrf token
//$csrf = $client->extractCsrf($html);

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

//Добавляем csrf-token
//foreach ($csrf as $token) {
//    $data['csrftoken'] = $token['csrftoken'];
//}

// Отправляем POST-запрос с обновленными данными
$firstResponse = $client->postFormData($url, $data);

// Обрабатываем ответ

// Извлекаем сообщение из элемента div[id="alert"]
$alertMessage = $client->extractAlertMessage($firstResponse);

// Проверяем, есть ли сообщение об ошибке
if ($alertMessage) {
    echo $alertMessage;
} else {
    // Извлекаем значения атрибута value из элементов input[readonly=""]
    $previousReadings = $client->extractReadonlyValues($firstResponse);

    // Используем значения атрибута value
//    foreach ($readonlyValues as $value) {}
    // Делаем что-то с каждым значением
    $data = [
        'reading1' => $newReadings,
    ];

    $hiddenFields = $client->extractHiddenFields($firstResponse);
    foreach ($hiddenFields as $field) {
        $data[$field['name']] = $field['value'];
    }

    // Отправляем POST-запрос с новыми данными
    $secondResponse = $client->postFormData($url, $data);
    echo $secondResponse;
    die();
    $alertMessage = $client->extractAlertMessage($secondResponse);
    if ($alertMessage) {
        echo $alertMessage;
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
        dd($previousReadings[0]);

//        $thirdResponse = $client->postFormData($url, $data);
//        echo $thirdResponse;

        // Обрабатываем ответ
        // ...
    }
}

// Выводим HTML-код ответа
//echo $response;
