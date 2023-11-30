<?php

namespace App\Http\Telegraph;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;

class ReadingHandler
{
    private $client;
    private $cookieJar;

    public function __construct()
    {
        // Создаем новый экземпляр Guzzle HTTP клиента
        $this->client = new Client();
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
     * Извлекаем сообщение из элемента div[id="alert"] на странице.
     *
     * @param string $html HTML-код страницы
     * @return string|null Сообщение или null, если элемент не найден
     */
    public function extractAlertMessage($html)
    {
        $crawler = new Crawler($html);
        $alertMessage = $crawler->filter('div[id="alert"] span[class="msg"]')->text();

        return $alertMessage ?: null;
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
}
//показания к передаче
$readings = '';
// Создаем экземпляр класса ReadingHandler
$client = new ReadingHandler();

// Определяем URL веб-сайта
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
$response = $client->postFormData($url, $data);

// Обрабатываем ответ

// Извлекаем сообщение из элемента div[id="alert"]
$alertMessage = $client->extractAlertMessage($html);

// Проверяем, есть ли сообщение об ошибке
if ($alertMessage) {
    echo "Ошибка: " . $alertMessage;
} else {
    // Извлекаем значения атрибута value из элементов input[readonly=""]
    $readonlyValues = $client->extractReadonlyValues($html);

    // Обрабатываем успешный ответ
    // ...

    // Используем значения атрибута value
    foreach ($readonlyValues as $value) {
        // Делаем что-то с каждым значением
        // ...
    }

    // Отправляем POST-запрос с новыми данными
    $response = $client->postFormData($url, []);

    // Обрабатываем ответ
    // ...
}

// Выводим HTML-код ответа
echo $response;
