<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/23/19
 * Time: 7:37 PM
 */

namespace App;


use Casperlaitw\LaravelFbMessenger\Contracts\BaseHandler;
use Casperlaitw\LaravelFbMessenger\Messages\ReceiveMessage;
use Casperlaitw\LaravelFbMessenger\Messages\Text;


class MyHandler extends BaseHandler
{
    protected $payload = 'USER_DEFINED_PAYLOAD';

    public function handle(ReceiveMessage $message)
    {
        $this->send(new Text($message->getSender(), "Hello world"));
    }
}
