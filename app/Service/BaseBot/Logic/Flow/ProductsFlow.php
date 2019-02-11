<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/11/19
 * Time: 11:57 AM
 */

namespace App\Service\BaseBot\Logic\Flow;

use App\BaseModels\Product;
use App\BaseModels\ProductGroup;
use App\Service\BaseBot\BaseBot;
use App\Service\BaseBot\Logic\Logic;

trait ProductsFlow
{

    public function Pr_sendTextProductGroup()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_PR_SEND_TEXT_PRODUCT_GROUP);
        $userText = $this->bot->getUserText();

        $currentPage = $this->bot->getCurrentPageProductGroup();
        $currentPage = ($userText != Logic::BUTTON_BACK) ? $currentPage : (($currentPage > 1) ? --$currentPage : $currentPage);
        $currentPage = ($userText == Logic::BUTTON_FORWARD) ? ++$currentPage : $currentPage;
        $this->bot->setCurrentPageProductGroup($currentPage);
        $offset = ($currentPage - 1) * 9;
        $limit = $currentPage * 9;


        $productGroup = ProductGroup::where('name', $userText)->get();
        if (!$productGroup->isEmpty()) {
            $this->bot->setProductGroupId($productGroup->first()->id);
            $this->Pr_sendTextProducts();
            exit;
        }

        $product = Product::where("name", "LIKE", "%{$userText}%")->get();
        if (!$product->isEmpty()) {
            $this->Pr_sendTextProducts();
            exit;
        }


        $this->bot->setText('Виберіть із списку групу в яку входить препарат');
        $prodGroupNames = ProductGroup::where("name", "LIKE", "%{$userText}%")
            ->offset($offset)
            ->limit($limit)
            ->pluck('name')
            ->toArray();

        if (empty($prodGroupNames)) {
            $this->bot->setText('Введіть перші букви назви препарату або виберіть із списку групу в яку входить препарат');
            $prodGroupNames = ProductGroup::offset($offset)
                ->limit($limit)
                ->pluck('name')
                ->toArray();
        } else {
            $prodGroupNames[] = Logic::BUTTON_ALL_PRODUCT_GROUP;
        }

        if (!empty($prodGroupNames)) {
            $prodGroupNames[] = Logic::BUTTON_FORWARD;
        }
        $prodGroupNames[] = Logic::BUTTON_BACK;
        $this->bot->setKeyboard($prodGroupNames);
        $this->bot->send(BaseBot::KEYBOARD);
    }

    public function Pr_sendTextProducts()
    {
        $this->bot->sendText('Pr_sendTextProducts');
    }
}