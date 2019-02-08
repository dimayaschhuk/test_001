<?php


use Viber\Api\Keyboard;
use Viber\Api\Keyboard\Button;
use Viber\Api\Sender;
use Viber\Bot;


//if (!function_exists('getFlow')) {
//    function getFlow()
//    {
//        return [
//            'testFlow' => [
//                'welcome',
//                'sendTextCulture',
//                'searchCulture',
//                'selectCulture',
//                'sendTextProblemGroup',
//                'selectProblemGroup',
//                'sendTextProblem',
//                //                'searchProblem',
//                'selectProblem',
//                'searchProduct',
//                'selectProduct',
//            ],
//        ];
//
//    }
//}


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
        $bot = new Bot(['token' => '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f']);
        $bot->getClient()->sendMessage(
            (new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setReceiver($baseBot->getId())
                ->setText($baseBot->getText())
        );
    }
}


if (!function_exists('send_keyboard')) {
    function send_keyboard($typeBot, \App\Service\BaseBot\BaseBot $baseBot)
    {
        if ($typeBot == \App\Service\BaseBot\BaseBot::TYPE_TELGRAM) {
            send_keyboard_telegram($baseBot);
        }

        if ($typeBot == \App\Service\BaseBot\BaseBot::TYPE_VIBER) {
            send_keyboard_viber($baseBot);
//            send_keyboard_viber_Test($baseBot);
        }
    }
}

if (!function_exists('send_keyboard_telegram')) {
    function send_keyboard_telegram(\App\Service\BaseBot\BaseBot $baseBot)
    {
        $keyboard = $baseBot->getKeyboard();
        $countButtons = count($keyboard);
        if ($countButtons > 3) {
            $keyboard = array_chunk($keyboard, 3);
        } else {
            $keyboard = [$keyboard];
        }

        $reply_markup = \Telegram::replyKeyboardMarkup([
            'keyboard'          => $keyboard,
            'resize_keyboard'   => TRUE,
            'one_time_keyboard' => TRUE,
        ]);
        $response = \Telegram::sendMessage([
            'chat_id'      => $baseBot->getId(),
            'text'         => $baseBot->getText(),
            'reply_markup' => $reply_markup,
        ]);
        $response->getMessageId();
    }
}


if (!function_exists('send_keyboard_viber')) {
    function send_keyboard_viber(\App\Service\BaseBot\BaseBot $baseBot)
    {
        $botSender = new Sender([
            'name'   => 'mySzrBot',
            'avatar' => 'http://chat.organic.mobimill.com/storage/app/public/10/1e7bc03379018d5cfd8a2bb60af3592a.jpg',
        ]);

        $bot = new Bot(['token' => '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f']);
        $keyboard = new Keyboard();
        $buttons = [];
        $rows = 1;
        $columns = 3;
//        if (count($baseBot->getKeyboard()) % 3 == 0) {
//            $columns = 3;
//        }
//        $columns = (count($baseBot->getKeyboard()) > 1) ? 3 : 6;
//
//        if (count($baseBot->getKeyboard()) > 3) {
//            $rows = ceil(count($baseBot->getKeyboard()) / 3);
//            $columns = 2;
//        }

        foreach ($baseBot->getKeyboard() as $item) {
            $button = new Button();
            $button->setColumns($columns);
            $button->setRows($rows);
            $button->setBgColor("#2db9b9");
            $button->setActionBody($item);
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
                ->setText($baseBot->getText())
                ->setKeyboard($keyboard)
                ->setSender($botSender)
                ->setReceiver($baseBot->getId())
        );
    }
}

if (!function_exists('send_keyboard_viber_Test')) {
    function send_keyboard_viber_Test(\App\Service\BaseBot\BaseBot $baseBot)
    {
        $botSender = new Sender([
            'name'   => 'mySzrBot',
            'avatar' => 'http://chat.organic.mobimill.com/storage/app/public/10/1e7bc03379018d5cfd8a2bb60af3592a.jpg',
        ]);

        $bot = new Bot(['token' => '492df57f7927d70b-bb1dffe5ee14eea0-4498222180f6797f']);
        $keyboard = new Keyboard();
        $buttons = [];
        $rows = 1;
        $columns = 6;


        $button = new Button();
        $button->setColumns($columns);
        $button->setRows($rows);
        $button->setBgColor("#2db9b9");
        $button->setActionBody("asd");
        $button->setText("test");
        $button->setTextVAlign('middle');
        $button->setTextHAlign('center');
        $button->setTextOpacity(60);
        $button->setTextSize('regular');


        $keyboard->setBgColor("#FFFFFF");
        $keyboard->setDefaultHeight(TRUE);
        $keyboard->setButtons([$button]);

        $bot->getClient()->sendMessage(
            (new \Viber\Api\Message\Text())
                ->setText($baseBot->getText())
                ->setKeyboard($keyboard)
                ->setSender($botSender)
                ->setReceiver($baseBot->getId())
        );
    }
}


//
//if (!function_exists('get_keyboard_to_viber')) {
//    function get_keyboard_to_viber($keyboard)
//    {
//        $keyboard = get_keyboard_to_viber($baseBot->getKeyboard());
//
//        $reply_markup = \Telegram::replyKeyboardMarkup([
//            'keyboard'          => $keyboard,
//            'resize_keyboard'   => TRUE,
//            'one_time_keyboard' => TRUE,
//        ]);
//        $response = \Telegram::sendMessage([
//            'chat_id'      => $baseBot->getId(),
//            'text'         => $baseBot->getText(),
//            'reply_markup' => $reply_markup,
//        ]);
//        $response->getMessageId();
//    }
//}
//
//
//
//
//if (!function_exists('send_keyboard')) {
//    function send_keyboard($data)
//    {
//        $key = array_search($data['method'], getFlow()[$data['flow']]);
//
//        return getFlow()[$data['flow']][$key + 1];
//    }
//}
//
//if (!function_exists('next_method')) {
//    function next_method($data)
//    {
//        $key = array_search($data['method'], getFlow()[$data['flow']]);
//
//        return getFlow()[$data['flow']][$key + 1];
//    }
//}
//
//if (!function_exists('get_keyboard')) {
//    function get_keyboard($keyboard)
//    {
//
//
//        return $keyboard;
//    }
//}
//
//if (!function_exists('send_text')) {
//    function send_text($chatId, $text = 'text')
//    {
//        $response = \Telegram::sendMessage([
//            'chat_id' => $chatId,
//            'text'    => $text,
//        ]);
//        $response->getMessageId();
//    }
//}
//
//



