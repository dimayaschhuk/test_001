<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/11/19
 * Time: 11:57 AM
 */

namespace App\Service\BaseBot\Logic\Flow;


use App\Service\BaseBot\BaseBot;

trait ProductsFlow
{

    public function sendTextProductGroup()
    {
        $this->bot->setText('Введіть перші букви назви препарату або виберіть із списку групу в яку входить препарат');
        $currentPage = $this->bot->currentPageProductGroup;
        $prodGroupNames = \App\BaseModels\ProductGroup::offset($currentPage-1*9)
            ->limit($currentPage*9)
            ->pluck('name')
            ->toArray();
        $prodGroupNames[]='Назад';
        $prodGroupNames[]='Вперед';
        $this->bot->setKeyboard($prodGroupNames);
        $this->bot->send(BaseBot::KEYBOARD);
    }
}