<?php

namespace App\Http\Controllers;

use App\Commands\SendMessageCommand;
use Telegram;


class TelegramController extends Controller
{
    public function webhook(){
        $telegramUser=\Telegram::getWebhookUpdates()['message'];

        $SendMessageCommand= new SendMessageCommand();
        $SendMessageCommand->handle('ff');

        Telegram::commandsHandler(TRUE);
    }
}
