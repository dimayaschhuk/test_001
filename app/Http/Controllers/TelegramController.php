<?php

namespace App\Http\Controllers;

use App\Commands\SendMessageCommand;
use Telegram;


class TelegramController extends Controller
{
    public function webhook(){
        $telegramUser=\Telegram::getWebhookUpdates()['message'];
        

        $response = \Telegram::sendMessage([
            'chat_id' => $telegramUser['from']['id'],
            'text' => 'qqqqqq',
        ]);
        $response->getMessageId();


        Telegram::commandsHandler(TRUE);
    }
}
