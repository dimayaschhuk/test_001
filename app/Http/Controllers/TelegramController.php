<?php

namespace App\Http\Controllers;

use App\Commands\SendMessageCommand;
use Illuminate\Http\Request;
use Telegram;


class TelegramController extends Controller
{
    public function webhook()
    {
        $telegramUser = \Telegram::getWebhookUpdates()['message'];

        if (session($telegramUser['from']['id'])) {
            $response = \Telegram::sendMessage([
                'chat_id' => $telegramUser['from']['id'],
                'text'    => (string)session($telegramUser['from']['id']),
            ]);
        } else {
            session([$telegramUser['from']['id'] => 'value']);
            $response = \Telegram::sendMessage([
                'chat_id' => $telegramUser['from']['id'],
                'text'    => 'welcome',
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
