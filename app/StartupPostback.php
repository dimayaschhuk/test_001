<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/23/19
 * Time: 7:35 PM
 */

namespace App;

use App\Service\BaseBot\BaseBot;
use Casperlaitw\LaravelFbMessenger\Contracts\PostbackHandler;
use Casperlaitw\LaravelFbMessenger\Messages\ReceiveMessage;
use Casperlaitw\LaravelFbMessenger\Messages\Text;
use Illuminate\Support\Facades\Cache;

class StartupPostback extends PostbackHandler
{

    protected $payload = 'test'; // You also can use regex!


    public function handle(ReceiveMessage $message)
    {
        $chatId = $message->getSender();
        $text = $message->getMessage();
        $text = $message->getPostback();
        if (Cache::has(BaseBot::TYPE_FB . "/" . $chatId)) {
            $baseBot = Cache::get(BaseBot::TYPE_FB . "/" . $chatId);
            $baseBot->setUserText($text);
            $baseBot->runMethod();


        } else {
            $baseBot = new BaseBot(BaseBot::TYPE_FB, $chatId);
            $baseBot->setUserText($text);
            $baseBot->runMethod();

            Cache::put(BaseBot::TYPE_FB . "/" . $chatId, $baseBot, BaseBot::TIME_CACHE);
        }
    }
}