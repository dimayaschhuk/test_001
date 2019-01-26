<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{

    public function setWebHook(Request $request)
    {
        $url = 'https://www.dimayashchuk.icu';
        $result = $this->sendTelegramData('setwebhook', [
            'query' => ['url' => $url . '/' . \Telegram::getAccessToken()],
        ]);
        $request->session()->put('q','q');

        dd($result, $request->session()->get('q'));
    }

    public function getWebHookInfo(Request $request)
    {
        $result = $this->sendTelegramData('getWebhookInfo');

        dd($result, Cache::get(563738410));
    }

    public function sendTelegramData($route = '', $params = [], $method = 'POST')
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.telegram.org/bot' . \Telegram::getAccessToken() . '/']);
        $result = $client->request($method, $route, $params);

        return (string)$result->getBody();
    }


}
