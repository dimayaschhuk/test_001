<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/7/19
 * Time: 3:32 PM
 */

namespace App\Service\BaseBot\Logic;


trait Methods
{
    public function afterSelectedProduct()
    {
        $this->bot->sendText('afterSelectedProduct');
    }
}