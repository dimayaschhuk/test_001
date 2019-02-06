<?php

use Illuminate\Support\Facades\Cache;
use Viber\Api\Sender;

if (!function_exists('getFlow')) {
    function getFlow()
    {
        return [
            'testFlow' => [
                'welcome',
                'sendTextCulture',
                'searchCulture',
                'selectCulture',
                'sendTextProblemGroup',
                'selectProblemGroup',
                'sendTextProblem',
                //                'searchProblem',
                'selectProblem',
                'searchProduct',
                'selectProduct',
            ],
        ];

    }
}


if (!function_exists('send_text')) {
    function send_text($typeBot, \App\Service\BaseBot\BaseBot $baseBot)
    {
        if ($typeBot == \App\Service\BaseBot\BaseBot::TYPE_TELGRAM) {
            send_text_telegram($baseBot);
        }

        if ($typeBot == \App\Service\BaseBot\BaseBot::TYPE_VIBER) {
            send_text_viber($baseBot);
        }
    }
}

if (!function_exists('send_text_telegram')) {
    function send_text_telegram(\App\Service\BaseBot\BaseBot $baseBot)
    {
        $response = \Telegram::sendMessage([
            'chat_id' => $baseBot->getId(),
            'text'    => $baseBot->getText(),
        ]);
        $response->getMessageId();
    }
}

if (!function_exists('send_text_viber')) {
    function send_text_viber(\App\Service\BaseBot\BaseBot $baseBot)
    {
        $botSender = new Sender([
            'name'   => 'mySzrBot',
            'avatar' => 'http://chat.organic.mobimill.com/storage/app/public/10/1e7bc03379018d5cfd8a2bb60af3592a.jpg',
        ]);
        $bot = $baseBot->getViberBot();
        $bot->getClient()->sendMessage(
            (new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($baseBot->getId())
                ->setText($baseBot->getText())
        );
    }
}


if (!function_exists('send_keyboard')) {
    function send_keyboard($data)
    {
        $key = array_search($data['method'], getFlow()[$data['flow']]);

        return getFlow()[$data['flow']][$key + 1];
    }
}

if (!function_exists('next_method')) {
    function next_method($data)
    {
        $key = array_search($data['method'], getFlow()[$data['flow']]);

        return getFlow()[$data['flow']][$key + 1];
    }
}

if (!function_exists('get_keyboard')) {
    function get_keyboard($keyboard)
    {
        $countButtons = count($keyboard);
        if ($countButtons > 3) {
            $keyboard = array_chunk($keyboard, 3);
        } else {
            return [$keyboard];
        }

        return $keyboard;
    }
}

if (!function_exists('send_text')) {
    function send_text($chatId, $text = 'text')
    {
        $response = \Telegram::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
        ]);
        $response->getMessageId();
    }
}


if (!function_exists('send_keyboard')) {
    function send_keyboard($chatId, $keyboard, $text = 'text')
    {
        $reply_markup = \Telegram::replyKeyboardMarkup([
            'keyboard'          => $keyboard,
            'resize_keyboard'   => TRUE,
            'one_time_keyboard' => TRUE,
        ]);
        $response = \Telegram::sendMessage([
            'chat_id'      => $chatId,
            'text'         => $text,
            'reply_markup' => $reply_markup,
        ]);
        $response->getMessageId();
    }
}


