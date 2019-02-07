<?php

namespace App\Http\Controllers;

use App\Service\BaseBot\BaseBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebBotController extends Controller
{


    public function webhook($text = "null")
    {
        $chatId = 563738410;

        if (Cache::has(BaseBot::TYPE_TELGRAM . "/" . $chatId)) {
            $baseBot = Cache::get(BaseBot::TYPE_TELGRAM . "/" . $chatId);
            $baseBot->setUserText($text);
            $baseBot->runMethod();

        } else {
            $baseBot = new BaseBot(BaseBot::TYPE_TELGRAM, $chatId);
            $baseBot->setUserText($text);
            $baseBot->runMethod();

            Cache::put(BaseBot::TYPE_TELGRAM . "/" . $chatId, $baseBot, BaseBot::TIME_CACHE);
        }


    }
}
