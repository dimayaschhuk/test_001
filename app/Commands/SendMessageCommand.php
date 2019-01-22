<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 1/22/19
 * Time: 8:06 PM
 */

namespace App\Commands;


use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class SendMessageCommand extends Command
{
    public function handle($arguments)
    {
        $this->replyWithChatAction(['action'=>Actions::TYPING]);

        $this->replyWithMessage(['text'=>'laravel']);
        $text=sprintf('%s'.PHP_EOL,'test');
        $this->replyWithMessage(compact('text'));

    }
}