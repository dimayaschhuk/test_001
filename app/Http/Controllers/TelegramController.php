<?php

namespace App\Http\Controllers;

use App\Commands\SendMessageCommand;
use Telegram;


class TelegramController extends Controller
{
    public function webhook(){
        $telegramUser=\Telegram::getWebhookUpdates()['message'];
        $text=$telegramUser['text'];

        if($text=='Защита культур'){
            $response = \Telegram::sendMessage([
                'chat_id' => $telegramUser['from']['id'],
                'text' => 'Защита культур',
            ]);
            $response->getMessageId();
        }



        Telegram::commandsHandler(TRUE);
    }
}
