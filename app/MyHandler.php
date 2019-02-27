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
use function PHPSTORM_META\elementType;


class MyHandler extends BaseHandler
{
    public function handle(ReceiveMessage $message)
    {

        $chatId = $message->getSender();
        $text = $message->getMessage();
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

    public function sendMessage($sender, $text)
    {

        $message = new ReceiveMessage($sender, $sender);
        $message->setMessage($text);
        $this->createBot(env('PAGE_ACCESS_TOKEN'));
        $this->send(new Text($message->getSender(), $message->getMessage()));

    }

    public function sendButton($sender, $keyboards, $text)
    {

        $message = new ReceiveMessage($sender, $sender);
        $this->createBot(env('PAGE_ACCESS_TOKEN'));
        $message->setMessage('');
        $keyboards = array_chunk($keyboards, 3);
        $q = 0;
        foreach ($keyboards as $keyboard) {
            $button = new ButtonTemplate($message->getSender());
//            if ($q === 0) {
//
//            } else {
//                $message->setMessage('');
//            }
            $button->setText($message->getMessage());
            $q++;
            for ($i = 0; $i < count($keyboard); $i++) {
                $button->addPostBackButton($keyboard[$i]);
            }
            $this->send($button);
        }
    }

//
    public function sendKeyboard($sender, $keyboards, $textx)
    {
        $message = new ReceiveMessage($sender, $sender);
        $message->setMessage($textx);
        $this->createBot(env('PAGE_ACCESS_TOKEN'));
        $text = new Text($message->getSender(), "Default Handler: {$message->getMessage()}");
        foreach ($keyboards as $keyboard) {
            $text->addQuick(new QuickReply($keyboard, 'test'));
        }


        $this->send($text);
    }
}
