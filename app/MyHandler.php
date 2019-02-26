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


class MyHandler extends BaseHandler
{
    public function handle(ReceiveMessage $message)
    {
        $text = $message->getMessage();
        $chatId = $message->getSender();

        if (Cache::has(BaseBot::TYPE_FB . "/" . $chatId)) {
//            $baseBot = Cache::get(BaseBot::TYPE_FB . "/" . $chatId);
//            $baseBot->sendText('True');
//            $baseBot->setUserText($text);
//            $baseBot->runMethod();
            $this->send(new Text($chatId, 'RUN'));
//
        } else {
//            $this->send(new Text($chatId, 'START_1'));
//            $this->send(new Text($chatId, 'false'));
            $baseBot = new BaseBot(BaseBot::TYPE_FB, $chatId);
//            $baseBot->setUserText($text);
            $baseBot->sendText('START_2');
//            $baseBot->runMethod();
//
            Cache::put(BaseBot::TYPE_FB . "/" . $chatId, $baseBot, BaseBot::TIME_CACHE);
        }


    }

    public function sendMessage($sender,$text)
    {
        $this->send(new Text($sender, $text));
    }

    public function sendButton($sender, $keyboards, $text)
    {
        $button = new ButtonTemplate($sender, $text);
        $button->setText('Choose');
        foreach ($keyboards as $keyboard) {
            $button->addPostBackButton($keyboard);
        }

        $this->send($button);
    }

    public function sendKeyboard($sender, $keyboards, $textx)
    {
        $text = new Text($sender, $textx);
        foreach ($keyboards as $keyboard) {
            $text->addQuick(new QuickReply($keyboard, $keyboard));
        }

        $this->send($text);
    }
}
