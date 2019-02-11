<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/7/19
 * Time: 3:32 PM
 */

namespace App\Service\BaseBot\Logic;


use App\Service\BaseBot\BaseBot;

trait Methods
{

    public function welcome()
    {
        $this->bot->setText("Виберіть гілку");
        $this->bot->setKeyboard(['Захист культури', 'Продукти']);
        $this->bot->send(BaseBot::KEYBOARD);
        $this->bot->setCurrentMethod(self::METHOD_SELECT_FLOW);
        exit;
    }

    public function afterSelectedProduct()
    {
        $this->bot->sendText('afterSelectedProduct');
    }
}