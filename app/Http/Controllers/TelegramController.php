<?php

namespace App\Http\Controllers;

use App\Commands\SendMessageCommand;
use Telegram;


class TelegramController extends Controller
{
    public function webhook(){
        $telegramUser=\Telegram::getWebhookUpdates()['message'];
        $text=$telegramUser['text'];

        session()->put($telegramUser['from']['id'],'');
        if(session()->has($telegramUser['from']['id'])){
            session()->get($telegramUser['from']['id']);
        }

        if($text=='Защита культур'){
            $response = \Telegram::sendMessage([
                'chat_id' => $telegramUser['from']['id'],
                'text' => 'Введіть назву або перші букви культур',
            ]);
            $response->getMessageId();
        }
        if($text=='/start'){
            Telegram::commandsHandler(TRUE);
        }




    }

    public function getFlow(){
        return [
            ''
        ];
    }
}
