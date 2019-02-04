<?php

namespace App\Http\Controllers;

use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Viber\Client;
use Viber\Bot;
use Viber\Api\Sender;

class ViberController extends Controller
{

    public function setWebhook(){
        $apiKey = '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f';
        $webhookUrl = 'https://www.dimayashchuk.icu/viber_bot'; //
        try {
            dump('try');
            $client = new Client([ 'token' => $apiKey ]);
            dump($client);
            $result = $client->setWebhook($webhookUrl);
            dd($result);
            echo "Success!\n";


        } catch (\ErrorException $e) {
            echo "Error: ". $e->getError() ."\n";
        }
    }

    public function webHook(){
        Log::info('start webHook');
        Log::info('1111111111111111111111111111111111111');
        $apiKey = '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f';

        $botSender = new Sender([
            'name' => 'mySzrBot',
            'avatar' => 'http://chat.organic.mobimill.com/storage/app/public/10/1e7bc03379018d5cfd8a2bb60af3592a.jpg',
        ]);
        Log::info('set $botSender');
        try {
            Log::info('try send message');
            $bot = new Bot(['token' => $apiKey]);
            $bot
                ->onConversation(function ($event) use ($bot, $botSender) {
                    return (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setText("Can i help you?");
                })
                ->onText('test', function ($event) use ($bot, $botSender) {
                    Log::info('SEND message');
                    $bot->getClient()->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($event->getSender()->getId())
                            ->setText("I do not know )")
                    );
                })
                ->run();
            Log::info('SEND MESSAGE');
        } catch (Exception $e) {
            Log::info('not send message');
        }
    }
}
