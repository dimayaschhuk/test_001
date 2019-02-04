<?php

namespace App\Http\Controllers;

use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Viber\Api\Keyboard;
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
            Log::info('try send message');
            $bot = new Bot(['token' => $apiKey]);
            $bot
                ->onConversation(function ($event) use ($bot, $botSender) {
                    return (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setText("Чим я можу вам допомогти?");
                })
                ->onText('|whois .*|si', function ($event) use ($bot, $botSender) {
                    Log::info('SEND message');
                    $bot->getClient()->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($event->getSender()->getId())
                            ->setText("I do not know )")
                    );
                })
                ->onText('|test .*|si', function ($event) use ($bot, $botSender) {
                    send_text_viber($bot, $botSender, $event, 'test');

                })
                ->onText('|key .*|si', function ($event) use ($bot, $botSender) {
                    $keyboard = new Keyboard();
                    $keyboard->setButtons(['test', 'test']);
                    $bot->getClient()->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($event->getSender()->getId())
                            ->setText("I do not know )")
                            ->setKeyboard($keyboard)
                    );
                })
                ->run();
        } catch (Exception $e) {
            Log::info('not send message');
        }
    }
}
