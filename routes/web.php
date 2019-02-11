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

use App\BaseModels\Culture;
use App\BaseModels\Problem;
use App\BaseModels\ProblemGroup;
use App\Service\BaseBot\BaseBot;
use App\Service\BaseBot\Logic\Logic;
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


Route::get('/testMyViberBot', function () {
    {
        $text='1111';
        $chatId="cT0AJq4mBsVbUX1ITQRd4w==";
        $baseBot = new BaseBot(BaseBot::TYPE_VIBER, $chatId);
        $baseBot->setUserText($text);
        $baseBot->runMethod();
        dd('testMyViberBot');
    }
});

Route::get('/testMyTelegramBot', function () {
    {
        $text='1111';
        $chatId="563738410";
        $baseBot = new BaseBot(BaseBot::TYPE_TELGRAM, $chatId);
        $baseBot->setUserText($text);
        $baseBot->runMethod();
        dd('testMyTelegramBot');
    }
});


Route::get('/testBot', function () {
    {
        $culture = Culture::find(233);
        $userText = 'Парша';

        if ($culture->checkProblem($userText)) {
           dd('stop');
            exit;
        }
        dd('dd');
        if (empty($culture->getProblemNames($this->bot->getProblemGroupId()))) {
            if ($culture->getProblemNames()) {
                $this->bot->sentText('До даної культури немає проблем');
                $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_CULTURE);
                $this->sendTextCulture();
                exit;
            }
            $this->bot->sentText('До даної культури не знайдено проблеми з цієї групи проблем');
            $this->sendTextProblemGroup();
            exit;
        } else {
            $this->bot->setKeyboard($culture->getProblemNames($this->bot->getProblemGroupId()));
            $this->bot->setText('Виберіть назву проблеми');
            $this->bot->send(BaseBot::KEYBOARD);
        }


    }
});

Route::get('/MIGRATE', function () {
    {
        $dir = '../data/dampDB';
        $files = scandir($dir);

        unset($files[0]);
        unset($files[1]);

        foreach ($files as $file) {
            $filename = $dir . "/" . $file;
            $templine = '';
            $lines = file($filename);
            foreach ($lines as $line) {

                if (substr($line, 0, 2) == '--' || $line == '') {
                    continue;
                }

                $templine .= $line;

                if (substr(trim($line), -1, 1) == ';') {

                    \Illuminate\Support\Facades\DB::select($templine);
//                mysqli_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error() . '<br /><br />');
                    $templine = '';
                }
            }
        }

        echo "Tables imported successfully";
    }
});


