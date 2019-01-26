<?php

use Illuminate\Support\Facades\Cache;

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
                'testM',
                'test',
            ],
        ];

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
    function send_text($chatId,$text = 'text')
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