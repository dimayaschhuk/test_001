<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/23/19
 * Time: 7:35 PM
 */

namespace App;

use Casperlaitw\LaravelFbMessenger\Contracts\PostbackHandler;
use Casperlaitw\LaravelFbMessenger\Messages\ReceiveMessage;
use Casperlaitw\LaravelFbMessenger\Messages\Text;

class StartupPostback extends PostbackHandler
{

    protected $payload = 'test'; // You also can use regex!


    public function handle(ReceiveMessage $message)
    {
        $this->send(new Text($message->getSender(), "I got your payload"));
    }
}