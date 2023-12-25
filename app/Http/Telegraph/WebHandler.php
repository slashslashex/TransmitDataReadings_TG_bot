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
