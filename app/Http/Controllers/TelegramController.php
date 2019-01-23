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
        $chatId = $telegramUser['from']['id'];
        $text = $telegramUser['text'];

        if (Cache::has($telegramUser['from']['id'])) {
            $data = Cache::get($telegramUser['from']['id']);
            if ($data['flow'] == 'testFlow') {
                $method = next_method($data);
//                $this->test($chatId,'next method: '.$method);
                if ($method == 'testMethod') {
                    $this->testMethod($chatId,$text);
                }
                if ($method == 'testMetho') {
                    $this->testMetho($chatId,$text);
                }
                if ($method == 'testMeth') {
                    $this->testMeth($chatId,$text);
                }
            }else{
                $this->test($chatId,'not testFlow');
            }
        } else {
            $value = ['flow' => 'testFlow', 'method' => 'welcome'];
            Cache::put($telegramUser['from']['id'], $value, 1);
            $response = \Telegram::sendMessage([
                'chat_id'      => $telegramUser['from']['id'],
                'text'         => 'Добро рожаловть',
            ]);
            $response->getMessageId();
            $keyboard = [
                ['Продукты', 'Защита культур'],
            ];
            $reply_markup = \Telegram::replyKeyboardMarkup([
                'keyboard'          => $keyboard,
                'resize_keyboard'   => TRUE,
                'one_time_keyboard' => TRUE,
            ]);
            $response = \Telegram::sendMessage([
                'chat_id'      => $chatId,
                'text'         => 'Виберіть гілку',
                'reply_markup' => $reply_markup,
            ]);
            $response->getMessageId();
        }


    }

    public function testMethod($chatId,$text)
    {
        $value = ['flow' => 'testFlow', 'method' => 'testMethod'];
        Cache::put($chatId, $value, 1);
        $response = \Telegram::sendMessage([
            'chat_id'      => $chatId,
            'text'         => 'Введіть назву культури або перші букви',
        ]);

        $response->getMessageId();
    }

    public function testMetho($chatId,$text)
    {
        $value = ['flow' => 'testFlow', 'method' => 'testMetho'];
        Cache::put($chatId, $value, 1);
        $keyboard = [
            ['Культура'],
        ];

        $reply_markup = \Telegram::forceReply([
            'keyboard'          => $keyboard,
            'resize_keyboard'   => TRUE,
            'one_time_keyboard' => TRUE,
        ]);

        $response = \Telegram::sendMessage([
            'chat_id'      => $chatId,
            'text'         => 'Виберіть із списка оду культуру яка вам найбільше підходить',
            'reply_markup' => $reply_markup,
        ]);

        $response->getMessageId();
    }

    public function testMeth($chatId,$text)
    {
        $value = ['flow' => 'testFlow', 'method' => 'testMeth'];
        Cache::put($chatId, $value, 1);
        $keyboard = [
            ['Група1','Група2'],
        ];

        $reply_markup = \Telegram::replyKeyboardMarkup([
            'keyboard'          => $keyboard,
            'resize_keyboard'   => TRUE,
            'one_time_keyboard' => TRUE,
        ]);

        $response = \Telegram::sendMessage([
            'chat_id'      => $chatId,
            'text'         => 'Введіть назву проблеми або виберіть із списка групу в яку входить ваша проблема',
            'reply_markup' => $reply_markup,
        ]);

        $response->getMessageId();
    }



    public function test($chatId,$text)
    {

        $response = \Telegram::sendMessage([
            'chat_id'      => $chatId,
            'text'         => 'test: '.$text,
        ]);

        $response->getMessageId();
    }


}
