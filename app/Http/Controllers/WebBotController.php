<?php

namespace App\Http\Controllers;

use App\Service\BaseBot\BaseBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebBotController extends Controller
{


    public function webhook($text = "Захист культури")
    {
        $chatId = "cT0AJq4mBsVbUX1ITQRd4w==";

        if (Cache::has("webBot")) {
            $baseBot = Cache::get("webBot");
            $baseBot->setUserText($text);
            $baseBot->runMethod();

        } else {

            $baseBot = new BaseBot(BaseBot::TYPE_VIBER, $chatId);
            $baseBot->setUserText($text);
            $baseBot->runMethod();

            Cache::put("webBot", $baseBot, BaseBot::TIME_CACHE);
        }


    }
}
