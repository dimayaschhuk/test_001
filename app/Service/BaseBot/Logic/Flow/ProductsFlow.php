<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/11/19
 * Time: 11:57 AM
 */

namespace App\Service\BaseBot\Logic\Flow;

use App\BaseModels\Brand;
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

        $product = Product::where("name", $userText)->first();
        if (!empty($product)) {
            $this->bot->setProductId($product->id);
            $this->afterSelectedProduct();
            exit;
        }

        $product = Product::where("name", "LIKE", "{$userText}%")->get();
        if (!$product->isEmpty() && $product->count() < 15) {
            $this->Pr_searchProducts();
            exit;
        }

        $productGroup = ProductGroup::where('name', $userText)->first();
        if (!empty($productGroup)) {
            $this->bot->setProductGroupId($productGroup->id);
            $this->Pr_sendTextBrand();
            exit;
        }

        $this->bot->setText('Виберіть із списку групу в яку входить препарат');
        $prodGroupNames = ProductGroup::where("name", "LIKE", "{$userText}%")->limit(14)->pluck('name')->toArray();

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

    public function Pr_searchProducts()
    {
        $userText = $this->bot->getUserText();
        $productNames = Product::where("name", "LIKE", "{$userText}%")
            ->limit(15)
            ->pluck('name')
            ->toArray();
        $this->bot->setText('Виберіть із списку препарат');

        $this->bot->setKeyboard($productNames);
        $this->bot->send(BaseBot::KEYBOARD);

    }

    public function Pr_sendTextBrand()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_PR_SEND_TEXT_BRAND);
        $productGroup = ProductGroup::find($this->bot->getProductGroupId());
        $userText = $this->bot->getUserText();


        $band = Brand::where('name', $userText)->first();

        $this->sendText('strlen: ' . strlen($userText));
        if (!empty($band)) {
            $this->bot->setBrandId($band->id);
            $this->Pr_sendTextProducts();
            exit;
        }


        if (empty($productGroup->getBrandNames())) {
            $this->bot->sendText('Брендів до цієї групи не знайдено (((');
            $this->bot->setText('');
            $this->bot->setCurrentMethod(Logic::METHOD_PR_SEND_TEXT_PRODUCT_GROUP);
            exit;
        } else {
            if (count($productGroup->getBrandNames()) == 1) {
                $this->bot->setText('До даної групи підходить тільки один бренд:' . $productGroup->getBrandNames()[0]);
                $band = Brand::where('name', $productGroup->getBrandNames()[0])->first();
                $this->bot->setBrandId($band->id);
                $this->Pr_sendTextProducts();
                exit;
            }
            $this->bot->setText('Виберіть із списку бренд');
            $this->bot->setKeyboard($productGroup->getBrandNames());
            $this->bot->send(BaseBot::KEYBOARD);
            exit;
        }


    }

    public function Pr_sendTextProducts()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_PR_SEND_TEXT_PRODUCT);
        $userText = $this->bot->getUserText();
        $productGroupId = $this->bot->getProductGroupId();
        $brandId = $this->bot->getBrandId();
        $currentPage = $this->bot->getCurrentPageProduct();
        $currentPage = ($userText != Logic::BUTTON_BACK) ? $currentPage : (($currentPage > 1) ? --$currentPage : $currentPage);
        $currentPage = ($userText == Logic::BUTTON_FORWARD) ? ++$currentPage : $currentPage;
        $this->bot->setCurrentPageProduct($currentPage);

        $productNames = Product::where("name", "LIKE", "{$userText}%")
            ->where('brandId', $brandId)
            ->limit(12)
            ->pluck('name')
            ->toArray();

        $product = Product::where("name", $userText)->where('brandId', $brandId)->first();
        if (!$product->isEmpty()) {
            $this->bot->setProductId($product->id);
            $this->afterSelectedProduct();
        }

        if (empty($productNames)) {
            $productNames = Product::where('groupId', $productGroupId)
                ->where('brandId', $brandId)
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