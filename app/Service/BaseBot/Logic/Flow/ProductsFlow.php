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

        if (!Product::where("name", "LIKE", "%{$userText}%")->get()->isEmpty()) {
            $this->Pr_sendTextProducts();
            exit;
        }

        $productGroup = ProductGroup::where('name', $userText)->get();
        if (!$productGroup->isEmpty()) {
            $this->bot->setProductGroupId($productGroup->first()->id);
            $this->Pr_sendTextProducts();
            exit;
        }

        $this->bot->setText('Виберіть із списку групу в яку входить препарат');
        $prodGroupNames = ProductGroup::where("name", "LIKE", "%{$userText}%")->limit(14)->pluck('name')->toArray();

        if (empty($prodGroupNames)) {
            $this->bot->setText('Введіть перші 3 букви назви препарату або виберіть із списку групу в яку входить препарат');
            $prodGroupNames = ProductGroup::offset(($currentPage - 1) * 15)->limit($currentPage * 15)->pluck('name')->toArray();

            if (!empty($prodGroupNames)) {
                $prodGroupNames[] = Logic::BUTTON_FORWARD;
            }
            $prodGroupNames[] = Logic::BUTTON_BACK;
        } else {
            $prodGroupNames[] = Logic::BUTTON_ALL_PRODUCT_GROUP;
        }

        $this->bot->setKeyboard($prodGroupNames);
        $this->bot->send(BaseBot::KEYBOARD);
    }

    public function Pr_sendTextProducts()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_PR_SEND_TEXT_PRODUCT);
        $userText = $this->bot->getUserText();
        $productGroupId = $this->bot->getProductGroupId();
        $currentPage = $this->bot->getCurrentPageProduct();
        $currentPage = ($userText != Logic::BUTTON_BACK) ? $currentPage : (($currentPage > 1) ? --$currentPage : $currentPage);
        $currentPage = ($userText == Logic::BUTTON_FORWARD) ? ++$currentPage : $currentPage;
        $this->bot->setCurrentPageProduct($currentPage);

        $productNames = Product::where("name", "LIKE", "{$userText}%")
            ->limit(12)
            ->pluck('name')
            ->toArray();

        $product = Product::where("name", $userText)->get();
        if (!$product->isEmpty()) {
            $this->bot->setProductId($product->first()->id);
            $this->afterSelectedProduct();

        }

        if (empty($productNames) && empty($productGroupId)) {
            $this->Pr_sendTextProductGroup();
            exit;
        }

        if (empty($productNames)) {
            $productNames = Product::where('groupId', $productGroupId)
                ->offset(($currentPage - 1) * 15)->limit($currentPage * 15)
                ->pluck('name')->toArray();

            if (!empty($productNames)) {
                $productNames[] = Logic::BUTTON_FORWARD;
            }
            $productNames[] = Logic::BUTTON_BACK;
        } else {
            $productNames[] = Logic::BUTTON_ALL_PRODUCT;
        }


        $this->bot->setText('Виберіть із списку препарат');

        $this->bot->setKeyboard($productNames);
        $this->bot->send(BaseBot::KEYBOARD);

    }



}