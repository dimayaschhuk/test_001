<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/11/19
 * Time: 11:57 AM
 */

namespace App\Service\BaseBot\Logic\Flow;


use App\Service\BaseBot\BaseBot;
use App\Service\BaseBot\Logic\Logic;

trait ProductsFlow
{

    public function sendTextProductGroup()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_PRODUCT_GROUP);
        $userText = $this->bot->getUserText();
        $currentPage = $this->bot->currentPageProductGroup;
        if ($userText == Logic::BUTTON_BACK) {
            if ($currentPage > 1) {
                $currentPage--;
            }
        }

        if ($userText == Logic::BUTTON_FORWARD) {
            $currentPage++;

        }

        $this->bot->setText('Введіть перші букви назви препарату або виберіть із списку групу в яку входить препарат');

        $prodGroupNames = \App\BaseModels\ProductGroup::offset($currentPage - 1 * 9)
            ->limit($currentPage * 9)
            ->pluck('name')
            ->toArray();
        $prodGroupNames[] = Logic::BUTTON_BACK;
        $prodGroupNames[] = Logic::BUTTON_FORWARD;


        $this->bot->setKeyboard($prodGroupNames);
        $this->bot->send(BaseBot::KEYBOARD);
    }
}