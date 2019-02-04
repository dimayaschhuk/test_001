<?php

require_once("../vendor/autoload.php");

use Viber\Bot;
use Viber\Api\Sender;

$apiKey = '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f';

// так будет выглядеть наш бот (имя и аватар - можно менять)
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