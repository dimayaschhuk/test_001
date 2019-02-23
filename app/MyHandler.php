<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/23/19
 * Time: 7:37 PM
 */

namespace App;


use Casperlaitw\LaravelFbMessenger\Contracts\BaseHandler;
use Casperlaitw\LaravelFbMessenger\Messages\ButtonTemplate;
use Casperlaitw\LaravelFbMessenger\Messages\QuickReply;
use Casperlaitw\LaravelFbMessenger\Messages\ReceiveMessage;
use Casperlaitw\LaravelFbMessenger\Messages\Text;


class MyHandler extends BaseHandler
{
    protected $payload = 'USER_DEFINED_PAYLOAD';

    public function handle(ReceiveMessage $message)
    {
//        $button = new ButtonTemplate($message->getSender(), 'Default text');
//        $button
//            ->setText('Choose')
//            ->addPostBackButton('First Bbutton')
//            ->addPostBackButton('Second Button')
//            ->addPostBackButton('Third button');
//        $this->send($button);

        $text = new Text($message->getSender(), "Default Handler: {$message->getMessage()}");
        $text->addQuick(new QuickReply('Red', 'PAYLOAD_RED'))
            ->addQuick(new QuickReply('Green', 'PAYLOAD_GREEN'));

        $this->send($text);
    }
}
