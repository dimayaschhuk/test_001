<?php

namespace App\Http\Controllers;

use App\Service\BaseBot\BaseBot;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Viber\Api\Event;
use Viber\Api\Keyboard;
use Viber\Api\Keyboard\Button;
use Viber\Client;
use Viber\Bot;
use Viber\Api\Sender;

class ViberController extends Controller
{

    public function setWebhook()
    {
        $apiKey = '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f';
        $webhookUrl = 'https://www.dimayashchuk.icu/viber_bot'; //
        try {
            dump('try');
            $client = new Client(['token' => $apiKey]);
            dump($client);
            $result = $client->setWebhook($webhookUrl);
            dd($result);
            echo "Success!\n";


        } catch (\ErrorException $e) {
            echo "Error: " . $e->getError() . "\n";
        }
    }

    public function webHook()
    {
        $apiKey = '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f';
        $botSender = new Sender([
            'name'   => 'mySzrBot',
            'avatar' => 'http://chat.organic.mobimill.com/storage/app/public/10/1e7bc03379018d5cfd8a2bb60af3592a.jpg',
        ]);
        try {
            $bot = new Bot(['token' => $apiKey]);

            $bot
                ->onConversation(function ($event) use ($bot, $botSender) {
                    return (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setText("Чим я можу вам допомогти?");
                })
                ->onText('(.*[а-я,a-z,0-9])', function ($event) use ($bot, $botSender) {

                    $text = $event->getMessage()->getText();
                    $id = $event->getSender()->getId();
                    if (Cache::has(BaseBot::TYPE_VIBER . "/" . $id)) {
                        $baseBot = Cache::get(BaseBot::TYPE_TELGRAM . "/" . $id);
                        $baseBot->setUserText($text);
                        $baseBot->runMethod();
                    } else {
                        $baseBot = new BaseBot(BaseBot::TYPE_VIBER, $id);
                        $baseBot->setUserText($text);
                        $baseBot->setViberBot($bot);
                        $baseBot->runMethod();
                    }

//
//                    $data['event'] = $event;
//                    $data['bot'] = $bot;
//                    $data['botSender'] = $botSender;

//                    send_text('Viber', $data, 'testText');
//                    send_text('Viber', $data, 'testText');
//                    $this->test('Viber', $data, 'testText');
//                    $keyboard = new Keyboard();
//                    $button = new Button();
//                    $keyboard->setBgColor("#FFFFFF");
//                    $keyboard->setDefaultHeight(TRUE);
//                    $button->setColumns(6);
//                    $button->setRows(1);
//                    $button->setBgColor("#2db9b9");
//                    $button->setActionBody("dfdfd");
//                    $button->setText('buttonTest');
//                    $button->setTextVAlign('middle');
//                    $button->setTextHAlign('center');
//                    $button->setTextOpacity(60);
//                    $button->setTextSize('regular');
//                    $keyboard->setButtons([$button]);
//
//                    $bot->getClient()->sendMessage(
//                        (new \Viber\Api\Message\Text())
//                            ->setText("342")
//                            ->setKeyboard($keyboard)
//                            ->setSender($botSender)
//                            ->setReceiver($event->getSender()->getId())
//
//                    );
                })
//                ->onText('|test .*|si', function ($event) use ($bot, $botSender) {
//
//                    send_text_viber(
//                        'viber',
//                        [
//                            'bot'       => $bot,
//                            'botSender' => $botSender,
//                            'event'     => $event,
//                        ], 'test');
//
//                })
                ->run();
        } catch (Exception $e) {
            Log::info('not send message');
        }
    }

    public function test($type, $data, $text)
    {
        send_text($type, $data, $text);
    }
}
