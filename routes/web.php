<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Service\BaseBot\BaseBot;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Viber\Api\Keyboard;
use Viber\Api\Sender;
use Viber\Bot;

Route::get('/', function () {
    return view('welcome');
});

Route::post(\Telegram::getAccessToken(), function () {
    app('App\Http\Controllers\TelegramController')->webhook();
});


Route::post('/viber_bot', 'ViberController@webHook');
Route::get('/viber_bot', 'ViberController@webHook');

Route::get('/web_bot/{text?}', 'WebBotController@webHook');

Route::get('/setWebHook', 'ViberController@setWebhook');

Route::get('/set', 'TestController@setWebHook');
Route::get('/get', 'TestController@getWebHookInfo');

Route::get('/deleteMyCacheViber', function () {
    {
        Cache::pull(BaseBot::TYPE_VIBER . "/" . "cT0AJq4mBsVbUX1ITQRd4w==");
        dd('delete viber...done');
    }
});

Route::get('/deleteMyCacheTelegram', function () {
    {
        Cache::pull(BaseBot::TYPE_TELGRAM . "/" . "563738410");
        dd('delete telegram...done');
    }
});

Route::get('/deleteMyCacheWeb', function () {
    {
        Cache::pull("webBot");
        dd('delete web...done');
    }
});

Route::get('/getMyCacheWeb', function () {
    {

        dd(Cache::get("webBot"));
    }
});

Route::get('/getMyCacheTelegram', function () {
    {

        dd(Cache::get(BaseBot::TYPE_TELGRAM . "/" . "563738410"));
    }
});

Route::get('/getMyCacheViber', function () {
    {
        dd(Cache::get(BaseBot::TYPE_VIBER . "/" . "cT0AJq4mBsVbUX1ITQRd4w=="));
    }
});


Route::get('/testBot', function () {
    {
        $chatId = 111111;
        $baseBot = new BaseBot(BaseBot::TYPE_TELGRAM, $chatId);
        $baseBot->setCurrentFlow(\App\Service\BaseBot\Logic\Logic::FLOW_PROTECT_CULTURE);
        $logic = new \App\Service\BaseBot\Logic\Logic($baseBot);


        try {
            $key = array_search($baseBot->currentFlow, $logic->getMethod()[$baseBot->currentFlow]);
            $baseBot->setCurrentMethod($this->getMethod()[$baseBot->currentFlow][$key + 1]);
            exit;
        } catch (ErrorException $errorException) {
            if (empty($baseBot->currentFlow)) {
                $this->bot->setCurrentMethod($this->getMethod()[$this->bot->currentFlow][0]);
                exit;
            }
        }

    }
});
