<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 1/22/19
 * Time: 7:33 PM
 */

namespace App\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected $name = 'start';
    protected $description = 'start command';

    public function handle($arguments)
    {
       $this->replyWithChatAction(['action'=>Actions::TYPING]);

       $telegramUser=\Telegram::getWebhookUpdates()['message'];

        $keyboard = [
            ['Продукты', 'Защита культур'],
        ];

        $reply_markup = \Telegram::replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $response = \Telegram::sendMessage([
            'chat_id' => $telegramUser['from']['id'],
            'text' => 'Hello World',
            'reply_markup' => $reply_markup
        ]);
        $messageId = $response->getMessageId();
        $this->replyWithMessage(compact('text'));
    }
}
