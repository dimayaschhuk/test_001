<?php

namespace App\Http\Controllers;

use App\Commands\SendMessageCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Telegram;


class TelegramController extends Controller
{
    public function webhook()
    {
        $telegramUser = \Telegram::getWebhookUpdates()['message'];






        if (Cache::has($telegramUser['from']['id'])) {
            $value = Cache::get('key');
            $response = \Telegram::sendMessage([
                'chat_id' => $telegramUser['from']['id'],
                'text'    => (string)$value,
            ]);
        }else{
            $value = 'Chat_id:'.$telegramUser['from']['id'];
            Cache::put($telegramUser['from']['id'],$value,10);
            $response = \Telegram::sendMessage([
                'chat_id' => $telegramUser['from']['id'],
                'text'    => 'ffffff',
            ]);
        }

        $response->getMessageId();
//        if($request->session()->has($telegramUser['from']['id'])){
//            $response = \Telegram::sendMessage([
//                'chat_id' => $telegramUser['from']['id'],
//                'text' => $request->session()->get($telegramUser['from']['id']),
//            ]);
//            $response->getMessageId();
//        }else{
//
//
//            $request->session($telegramUser['from']['id'],'вже є');
//        }
//        $response = \Telegram::sendMessage([
//            'chat_id' => $telegramUser['from']['id'],
//            'text'    => 'Введіть назву або перші букви культур',
//        ]);
//        $response->getMessageId();
//
//        if($text=='Защита культур'){

//        }
//
//        if($text=='Продукты'){
//            $keyboard = [
//                ['йцу', 'куй'],
//            ];
//
//            $reply_markup = \Telegram::replyKeyboardMarkup([
//                'keyboard' => $keyboard,
//                'resize_keyboard' => true,
//                'one_time_keyboard' => true
//            ]);
//
//            $response = \Telegram::sendMessage([
//                'chat_id' => $telegramUser['from']['id'],
//                'text' => 'Продукты',
//                'reply_markup' => $reply_markup
//            ]);
//            $messageId = $response->getMessageId();
//        }
//        if($text=='/start'){
////            Telegram::commandsHandler(TRUE);
//        }


    }

    public function getFlow()
    {
        return [
            '',
        ];
    }
}
