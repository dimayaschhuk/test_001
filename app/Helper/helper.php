<?php

use Illuminate\Support\Facades\Cache;
use Viber\Api\Keyboard;
use Viber\Api\Keyboard\Button;

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
                'searchProblem',
                'selectProblem',
                'searchProduct',
                'selectProduct',
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

if (!function_exists('send_keyboard')) {
    function send_keyboard($type, $data)
    {
        if ($type == "Telegram") {
            get_keyboard_Telegram($data);
        }


        if ($type == "Viber") {
            get_keyboard_Viber($data);
        }
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

if (!function_exists('send_text')) {
    function send_text($type, $data, $text = 'text')
    {
        if ($type == 'Telegram') {
            send_text_telegram($data, $text);
        }

        if ($type == 'Viber') {
            send_text_viber($data, $text);
        }

    }
}

if (!function_exists('get_keyboard_Viber')) {
    function get_keyboard_Viber($data)
    {
        $botSender = $data['botSender'];
        $bot = $data['bot'];
        $event = $data['event'];
        $keyboard = new Keyboard();
        $buttons = [];
        $rows = 1;
        $columns = (count($data['buttons']) > 1) ? 3 : 6;

        if (count($data['buttons']) > 3) {
            $rows = ceil(count($data['buttons']) / 3);
            $columns = 2;
        }

        foreach ($data['buttons'] as $item) {
            $button = new Button();
            $button->setColumns($columns);
            $button->setRows($rows);
            $button->setBgColor("#2db9b9");
            $button->setActionBody($item . "asd");
            $button->setText($item);
            $button->setTextVAlign('middle');
            $button->setTextHAlign('center');
            $button->setTextOpacity(60);
            $button->setTextSize('regular');
            $buttons[] = $button;
        }


        $keyboard->setBgColor("#FFFFFF");
        $keyboard->setDefaultHeight(TRUE);
        $keyboard->setButtons($buttons);

        $bot->getClient()->sendMessage(
            (new \Viber\Api\Message\Text())
                ->setText("342")
                ->setKeyboard($keyboard)
                ->setSender($botSender)
                ->setReceiver($event->getSender()->getId())

        );
    }
}

if (!function_exists('get_keyboard_Telegram')) {
    function get_keyboard_Telegram($data)
    {
        $keyboard = $data['keyboard'];
        $chatId = $data['chatId'];
        $text = $data['text'];

        $countButtons = count($keyboard);
        if ($countButtons > 3) {
            $keyboard = array_chunk($keyboard, 3);
        } else {
            return [$keyboard];
        }


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

        return $keyboard;
    }
}


if (!function_exists('send_text_Telegram')) {
    function send_text_Telegram($data, $text = 'text')
    {
        $chatId = $data['chatId'];
        $response = \Telegram::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
        ]);
        $response->getMessageId();
    }
}

if (!function_exists('send_text_Viber')) {
    function send_text_Viber($data, $text)
    {
        $bot = $data['bot'];
        $event = $data['event'];
        $botSender = $data['botSender'];
        $bot->getClient()->sendMessage(
            (new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($event->getSender()->getId())
                ->setText($text)
        );
    }
}