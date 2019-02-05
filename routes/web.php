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

Route::get('/setWebHook', 'ViberController@setWebhook');

Route::get('/set', 'TestController@setWebHook');
Route::get('/get', 'TestController@getWebHookInfo');

Route::get('/testViber', function () {
    {
        $apiKey = '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f';
        $botSender = new Sender([
            'name'   => 'mySzrBot',
            'avatar' => 'http://chat.organic.mobimill.com/storage/app/public/10/1e7bc03379018d5cfd8a2bb60af3592a.jpg',
        ]);
        $button = new \Viber\Api\Keyboard\Button();
        $button->setColumns(1);
        $button->setRows(1);
        $button->setBgColor('#fff');
        $button->setText('ds');
        $keyboard = new Keyboard();
        $keyboard->setBgColor('#fff');
        $keyboard->setDefaultHeight(TRUE);
        $keyboard->setButtons([$button]);
        dd($keyboard);
            $bot = new Bot(['token' => $apiKey]);

            $bot->onConversation(function ($event) use ($bot, $botSender) {
                    return (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setText("Чим я можу вам допомогти?");
                })
                ->onText('(.*[а-я,a-z,0-9])', function ($event) use ($bot, $botSender) {
                    $text = $event->getMessage()->getText();
                    $button = new \Viber\Api\Keyboard\Button();
                    $button->setColumns(1);
                    $button->setRows(1);
                    $button->setBgColor('#fff');
                    $button->setActionType('reply');
                    $button->setActionBody('reply to me');
                    $button->setText('buttonTest');
                    $button->setTextSize('regular');
                    $keyboard = new Keyboard();
                    $keyboard->setBgColor('#fff');
                    $keyboard->setButtons([$button]);

                    $bot->getClient()->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setKeyboard($keyboard)
                            ->setSender($botSender)
                            ->setReceiver($event->getSender()->getId())
                            ->setText("342")
                    );


                    $bot->getClient()->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setKeyboard($keyboard)
                            ->setSender($botSender)
                            ->setReceiver($event->getSender()->getId())
                            ->setText("342")
                    );
                })

                ->run();
//        } catch (Exception $e) {
//            dd('Exception',$e);
//            Log::info('not send message');
//        }

//        "receiver":"01234567890A=",
//   "type":"text",
//   "text":"Hello world",
//   "keyboard":{
//          "Type":"keyboard",
//          "DefaultHeight":true,
//          "Buttons":[
//              {
//                  "ActionType":"reply",
//                  "ActionBody":"reply to me",
//                  "Text":"Key text",
//                  "TextSize":"regular"
//         }
//      ]
//   }
    }
});