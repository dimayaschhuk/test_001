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
        $text
            ->addQuick(new QuickReply('1', '1'))
            ->addQuick(new QuickReply('2', '2'))
            ->addQuick(new QuickReply('3', '3'))
            ->addQuick(new QuickReply('4', '4'))
            ->addQuick(new QuickReply('5', '5'))
            ->addQuick(new QuickReply('6', '6'))
            ->addQuick(new QuickReply('7', '7'))
            ->addQuick(new QuickReply('8', '8'))
            ->addQuick(new QuickReply('9', '9'))
            ->addQuick(new QuickReply('10','10'))
            ->addQuick(new QuickReply('11','11'))
            ->addQuick(new QuickReply('12','12'))
            ->addQuick(new QuickReply('13','13'))
            ->addQuick(new QuickReply('14','14'))
            ->addQuick(new QuickReply('15','15'));


        $this->send($text);
    }
}
