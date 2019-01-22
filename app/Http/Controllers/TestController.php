<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{

    public function setWebHook()
    {
        $url = 'https://www.dimayashchuk.icu/';
        $result = $this->sendTelegramData('setwebhook', [
            'query' => ['url' => $url . '/' . \Telegram::getAccessToken()],
        ]);

        dd($result);
    }

    public function getWebHookInfo()
    {
        $result = $this->sendTelegramData('getWebhookInfo');

        dd($result);
    }

    public function sendTelegramData($route = '', $params = [], $method = 'POST')
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.telegram.org/bot' . \Telegram::getAccessToken() . '/']);
        $result = $client->request($method, $route, $params);

        return (string)$result->getBody();
    }
}
