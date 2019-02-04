<?php

namespace App\Http\Controllers;

use http\Exception;
use Illuminate\Http\Request;
use Viber\Client;
use Viber\Bot;
use Viber\Api\Sender;

class ViberController extends Controller
{

    public function setWebhook(){
        $apiKey = '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f';
        $webhookUrl = 'https://www.dimayashchuk.icu/viberBot.php'; //
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
        $apiKey = '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f';

        $botSender = new Sender([
            'name' => 'Whois bot',
            'avatar' => 'https://developers.viber.com/img/favicon.ico',
        ]);

        try {
            $bot = new Bot(['token' => $apiKey]);
            $bot
                ->onConversation(function ($event) use ($bot, $botSender) {
                    // это событие будет вызвано, как только пользователь перейдет в чат
                    // вы можете отправить "привествие", но не можете посылать более сообщений
                    return (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setText("Can i help you?");
                })
                ->onText('|whois .*|si', function ($event) use ($bot, $botSender) {
                    // это событие будет вызвано если пользователь пошлет сообщение
                    // которое совпадет с регулярным выражением
                    $bot->getClient()->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($event->getSender()->getId())
                            ->setText("I do not know )")
                    );
                })
                ->run();
        } catch (Exception $e) {
            // todo - log exceptions
        }
    }
}
