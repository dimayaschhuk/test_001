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
    protected $payload = 'USER_DEFINED_PAYLOAD';

    public function handle(ReceiveMessage $message)
    {
        $text = $message->getMessage();
        $chatId = $message->getSender();
        $this->send(new Text($chatId, 'start'));
        if (Cache::has(BaseBot::TYPE_FB . "/" . $chatId)) {
            $this->send(new Text($chatId, 'true'));
            $baseBot = Cache::get(BaseBot::TYPE_FB . "/" . $chatId);
            $baseBot->setUserText($text);
            $baseBot->runMethod();

        } else {
            $this->send(new Text($chatId, 'false'));
            $baseBot = new BaseBot(BaseBot::TYPE_FB, $chatId);
            $baseBot->setUserText($text);
            $baseBot->runMethod();

            Cache::put(BaseBot::TYPE_TELGRAM . "/" . $chatId, $baseBot, BaseBot::TIME_CACHE);
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
