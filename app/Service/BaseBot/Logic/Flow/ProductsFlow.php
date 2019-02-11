<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/11/19
 * Time: 11:57 AM
 */

namespace App\Service\BaseBot\Logic\Flow;


trait ProductsFlow
{

    public function sendTextProductGroup(){
        $this->bot->sendText('sendTextProductGroup');
    }
}