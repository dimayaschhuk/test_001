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

Route::post('/fb_bot', 'FBController@index');
Route::get('/fb_bot', 'FBController@index');

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

Route::get('/getMyCacheFB', function () {
    {
        dd(Cache::get(BaseBot::TYPE_FB . "/" . "2334437319914281"));
    }
});

Route::get('/deleteMyCacheFB', function () {
    {
        Cache::pull(BaseBot::TYPE_FB . "/" . "2334437319914281");
        dd('delete FB...done');
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

Route::get('/testMyFBBot', function () {

    $text = '1111';
    $chatId = "2334437319914281";
    $baseBot = new BaseBot(BaseBot::TYPE_FB, $chatId);
//        $baseBot->setUserText($text);

    $baseBot->setText('START_2');
    $baseBot->setKeyboard(['test_1', 'test_2']);
    $baseBot->send(BaseBot::KEYBOARD);
//        $baseBot->setKeyboard(['button_1','button_2','button_3','button_4','button_5','button_6','button_7']);
//        $baseBot->send(BaseBot::KEYBOARD);
    dd('testMyTelegramBot');

});


Route::get('/testBot', function () {

    $baseBot = new BaseBot(BaseBot::TYPE_TELGRAM, "web");
    $baseBot->setProductId(1);
    if ($baseBot->getCultureId()) {

    } else {
        $technologies = \App\BaseModels\Technology::where('productId', $baseBot->getProductId())->get();
        $text = [];
        foreach ($technologies as $technology) {
            $textTechnology = 'consumptionNormMin:' . $technology->consumptionNormMin . ","
                . "consumptionNormMax:" . $technology->consumptionNormMax . ","
                . "maxTreatmentCount:" . $technology->consumptionNormMax . ","
                . "amountUnit" . $technology->amountUnit . ","
                . "areaUnit" . $technology->areaUnit . ","
                . "consumptionNormMinFluid" . $technology->consumptionNormMinFluid . ","
                . "consumptionNormMaxFluid" . $technology->consumptionNormMaxFluid . ","
                . "amountUnitFluid" . $technology->amountUnitFluid . ","
                . "areaUnitFluid" . $technology->areaUnitFluid . ","
                . "watingTime" . $technology->watingTime . ","
                . "watingTerms" . $technology->watingTerms . ","
                . "features" . $technology->features . ",";
            $cultureId = \Illuminate\Support\Facades\DB::table('pd_CultureForCropProcessing')
                ->where('cropProcessingId', $technology->id)
                ->pluck('cultureId')
                ->toArray();
            $cultureNames = Culture::whereIn('id', $cultureId)
                ->pluck('name')
                ->toArray();
            $nameCulture = '';
            foreach ($cultureNames as $cultureName) {
                $nameCulture .= $cultureName . ',';
            }


            $text[] = ['technology' => $textTechnology, 'culture' => $nameCulture];
        }
        dd($text);
    }

    dd($baseBot);
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

Route::get('/rand', function () {
    dd(floor(collect([rand(1, 2), rand(2, 3), rand(3, 2), rand(2, 1), rand(1, 4)])->avg()));
});


