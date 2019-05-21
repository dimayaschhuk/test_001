<?php

namespace App\Http\Controllers;

use App\BaseModels\Culture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{

    public function setWebHook(Request $request)
    {
        $url = 'https://szrbot.mobimill.com';
        $result = $this->sendTelegramData('setwebhook', [
            'query' => ['url' => $url . '/' . \Telegram::getAccessToken()],
        ]);
        $request->session()->put('q','q');

        dd($result, $request->session()->get('q'));
    }

    public function getWebHookInfo(Request $request)
    {
        $result = $this->sendTelegramData('getWebhookInfo');

        $chatId = 563738410;
        $text = 'соя';
        $data['culture_id'] = Culture::where('name', $text)->value('id');
        Cache::put($chatId, $data, 1);
        $data = Cache::get($chatId);
        $culture = Culture::find($data['culture_id']);

        send_text($chatId, 'startее checkProblem');
        if ($culture->checkProblem($text)) {
            send_text($chatId,'checkProblem');
            exit;
        }
        send_text($chatId,'endууу checkProblem');

        dd($result, Cache::get(563738410));
    }

    public function sendTelegramData($route = '', $params = [], $method = 'POST')
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.telegram.org/bot' . \Telegram::getAccessToken() . '/']);
        $result = $client->request($method, $route, $params);

        return (string)$result->getBody();
    }


}
