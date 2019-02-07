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
                        $baseBot = Cache::get(BaseBot::TYPE_VIBER . "/" . $id);
                        $baseBot->setUserText($text);
                        $baseBot->setText('RUN');
                        $baseBot->send(BaseBot::TEXT);
//                        $baseBot->runMethod();
                    } else {
                        $baseBot = new BaseBot(BaseBot::TYPE_VIBER, $id);
                        $baseBot->setUserText($text);
                        $baseBot->setViberBot($bot);
                        $baseBot->setText((string)$id);
                        $baseBot->send(BaseBot::TEXT);
//                        $baseBot->runMethod();
                        Cache::put(BaseBot::TYPE_VIBER . "/" . $id, $baseBot, BaseBot::TIME_CACHE);
                    }

                })

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
