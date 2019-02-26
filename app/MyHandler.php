<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/23/19
 * Time: 7:37 PM
 */

namespace App;


use App\Service\BaseBot\BaseBot;

use Casperlaitw\LaravelFbMessenger\Contracts\BaseHandler;
use Casperlaitw\LaravelFbMessenger\Contracts\Messages\Message;
use Casperlaitw\LaravelFbMessenger\Messages\ButtonTemplate;
use Casperlaitw\LaravelFbMessenger\Messages\QuickReply;
use Casperlaitw\LaravelFbMessenger\Messages\ReceiveMessage;
use Casperlaitw\LaravelFbMessenger\Messages\Text;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Expr\Cast\Object_;


class MyHandler extends BaseHandler
{
    public function handle(ReceiveMessage $message)
    {
        $this->send(new Text($message->getSender(), "test_0"));

//        $chatId = $message->getSender();
        $chatId = "2334437319914281";
        $this->sendMessage($chatId,'test_1');
        $baseBot = new BaseBot(BaseBot::TYPE_FB, $chatId);
        $baseBot->sendText('test_2');

//        if (Cache::has(BaseBot::TYPE_FB . "/" . $chatId)) {
//            $baseBot = Cache::get(BaseBot::TYPE_FB . "/" . $chatId);
//            $baseBot->sendText('RUN_2');
////            $baseBot->setUserText($text);
////            $baseBot->runMethod();
////            $this->send(new Text($chatId, 'RUN'));
////
//        } else {
//            $baseBot = new BaseBot(BaseBot::TYPE_FB, $chatId);
////            $baseBot->setUserText($text);
//            $baseBot->sendText('START_2');
////            $baseBot->runMethod();
////
//            Cache::put(BaseBot::TYPE_FB . "/" . $chatId, $baseBot, BaseBot::TIME_CACHE);
//        }


    }

    public function sendMessage($sender, $text)
    {
        $message = new ReceiveMessage($sender,$sender);
        $message->setMessage($text);
        $this->createBot(env('PAGE_ACCESS_TOKEN'));
        $this->send(new Text($message->getSender(), "Test Handler: {$message->getMessage()}"));
    }

//    public function sendButton($sender, $keyboards, $text)
//    {
//        $button = new ButtonTemplate($sender, $text);
//        $button->setText('Choose');
//        foreach ($keyboards as $keyboard) {
//            $button->addPostBackButton($keyboard);
//        }
//
//        $this->send($button);
//    }
//
//    public function sendKeyboard($sender, $keyboards, $textx)
//    {
//        $text = new Text($sender, $textx);
//        foreach ($keyboards as $keyboard) {
//            $text->addQuick(new QuickReply($keyboard, $keyboard));
//        }
//
//        $this->send($text);
//    }
}
