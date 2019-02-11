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

use App\BaseModels\Brand;
use App\BaseModels\Culture;
use App\BaseModels\Problem;
use App\BaseModels\ProblemGroup;
use App\BaseModels\Product;
use App\BaseModels\ProductGroup;
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
        $text = '1111';
        $chatId = "cT0AJq4mBsVbUX1ITQRd4w==";
        $baseBot = new BaseBot(BaseBot::TYPE_VIBER, $chatId);
        $baseBot->setUserText($text);
        $baseBot->sendText('testMyViberBot');
        dd('testMyViberBot');
    }
});

Route::get('/testMyTelegramBot', function () {
    {
        $text = '1111';
        $chatId = "563738410";
        $baseBot = new BaseBot(BaseBot::TYPE_TELGRAM, $chatId);
        $baseBot->setUserText($text);
        $baseBot->sendText('testMyTelegramBot');
        dd('testMyTelegramBot');
    }
});


Route::get('/testBot', function () {
    {
        $userText = 'Ukravit';
        $productGroupId = 1;
        $brandId = 1;
        $currentPage = 1;
        $currentPage = ($userText != Logic::BUTTON_BACK) ? $currentPage : (($currentPage > 1) ? --$currentPage : $currentPage);
        $currentPage = ($userText == Logic::BUTTON_FORWARD) ? ++$currentPage : $currentPage;

        $productNames = Product::where("name", "LIKE", "{$userText}%")
            ->where('brandId', $brandId)
            ->limit(12)
            ->pluck('name')
            ->toArray();


        $product = Product::where("name", $userText)->where('brandId', $brandId)->first();
        if (!empty($product)) {
            $this->bot->setProductId($product->id);
            $this->afterSelectedProduct();
        }

        if (empty($productNames)) {
            $productNames = Product::where('groupId', $productGroupId)
                ->where('brandId', $brandId)
                ->offset(($currentPage - 1) * 15)->limit($currentPage * 15)
                ->pluck('name')->toArray();

            if (!empty($productNames)) {
                $productNames[] = Logic::BUTTON_FORWARD;
            }
            $productNames[] = Logic::BUTTON_BACK;
        } else {
            $productNames[] = Logic::BUTTON_ALL_PRODUCT;
        }

dd($productNames    );
        $this->bot->setText('Виберіть із списку препарат');

        $this->bot->setKeyboard($productNames);
        $this->bot->send(BaseBot::KEYBOARD);
    }
});

Route::get('/MIGRATE', function () {
    {
        $dir = '../data/dampDB';
        $files = scandir($dir);

        unset($files[0]);
        unset($files[1]);

        try {
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

                dump('Table successfully');
            }
        } catch (ErrorException $errorException) {
            dump('ErrorException');
        }


        dd('Table successfully');

    }
});


