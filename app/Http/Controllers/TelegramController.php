<?php

namespace App\Http\Controllers;

use Telegram;

class TelegramController extends Controller
{
    public function webhook(){
        Telegram::commandsHandler(TRUE);
    }
}
