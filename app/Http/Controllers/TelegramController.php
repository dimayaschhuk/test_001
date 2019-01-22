<?php

namespace App\Http\Controllers;

use Telegram;

class TelegramController extends Controller
{
    public function webhook(){
        $telegramUser=\Telegram::getWebhookUpdates()['message'];

        $this->replyWithMessage(['text'=>'laravel']);
        $text=sprintf('%s'.PHP_EOL,'test');
        $this->replyWithMessage(compact('text'));
        Telegram::commandsHandler(TRUE);
    }
}
