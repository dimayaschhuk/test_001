<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/8/19
 * Time: 5:01 PM
 */

namespace App\Service\BaseBot\Logic\Flow;


use App\BaseModels\Culture;
use App\BaseModels\Problem;
use App\BaseModels\ProblemGroup;
use App\BaseModels\Product;
use App\Service\BaseBot\BaseBot;
use App\Service\BaseBot\Logic\Logic;

trait ProtectCulture
{

    //=========good======================================


    public function sendTextCulture()
    {
        $this->bot->sendText('Введіть назву культури або перші 3 букви');
        $this->nextMethod();
        exit;
    }

    public function searchCulture()
    {
        try {
            $nameCultures = Culture::where('name', $this->bot->getUserText())->pluck('name')->toArray();
            $name = '';
            foreach ($nameCultures as $key => $nameCulture) {
                if ($name == $nameCulture) {
                    unset($nameCultures[$key]);
                }
                $name = $nameCulture;
            }
            if (count($nameCultures) === 1) {
                $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_PROBLEM_GROUP);
                $this->bot->setCultureId(Culture::where('name', $this->bot->getUserText())->value('id'));
                $this->sendTextProblemGroup();
                exit;
            }

            if (Culture::where('name', 'LIKE', "%{$this->bot->getUserText()}%")->count() === 0) {
                $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_CULTURE);
                $this->sendTextCulture();
                exit;
            }
            $cultureNames = Culture::where('name', 'LIKE', "{$this->bot->getUserText()}%")
                ->limit(12)
                ->pluck('name')
                ->toArray();

            $this->bot->setText('Виберіть із списка одну культуру яка вам найбільше підходить');
            $this->bot->setKeyboard($cultureNames);
            $this->bot->send(BaseBot::KEYBOARD);
        } catch (\ErrorException $e) {
            $this->bot->sendText('error searchCulture');
        }


    }

//==================================================


    public function sendTextProblemGroup()
    {

        try {
            $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_PROBLEM_GROUP);
            $culture = Culture::find($this->bot->getCultureId());
            $userText = $this->bot->getUserText();
            if ($culture->checkProblem($userText)) {
                $this->sendTextProblem();
                exit;
            }

            if ($culture->checkProblemGroup($userText)) {
                $this->bot->setProblemGroupId(ProblemGroup::where('name', $userText)->first()->id);
                $this->sendTextProblem();
                exit;
            }


            if (empty($culture->getProblemGroupNames())) {
                $this->bot->sendText('До даної культури немає продуктів');
                $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_CULTURE);
                $this->sendTextCulture();
            } else {

                $this->bot->setText('Виберіть із списка групу в яку входить ваша проблема');
                $this->bot->setKeyboard($culture->getProblemGroupNames());
                $this->bot->send(BaseBot::KEYBOARD);
                exit;

            }
        } catch (\ErrorException $errorException) {
            $this->bot->sendText('error sendTextProblemGroup');
        }


    }

    public function sendTextProblem()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_PROBLEM);
        $culture = Culture::find($this->bot->getCultureId());
        $userText = $this->bot->getUserText();

        if ($culture->checkProblem($userText)) {
            $this->bot->setProblemId(Problem::where('name', $userText)->first()->id);
            $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_PRODUCT);
            $this->sendTextProduct();
            exit;
        }

        if (empty($culture->getProblemNames($this->bot->getProblemGroupId()))) {
            if ($culture->getProblemNames()) {
                $this->bot->sentText('До даної культури немає проблем');
                $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_CULTURE);
                $this->sendTextCulture();
                exit;
            }
            $this->bot->sendText('До даної культури не знайдено проблеми з цієї групи проблем');
            $this->sendTextProblemGroup();
            exit;
        } else {
            $this->bot->setKeyboard($culture->getProblemNames($this->bot->getProblemGroupId()));
            $this->bot->setText('Виберіть назву проблеми');
            $this->bot->send(BaseBot::KEYBOARD);
        }


    }


    public function sendTextProduct()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_PRODUCT);
        $culture = Culture::find($this->bot->getCultureId());
        $productsNames = $culture->getProductsNames($this->bot->getProblemId());
        $problemId = $this->bot->getProblemId();
        $userText = $this->bot->getUserText();

        if ($culture->checkProduct($userText, $problemId)) {
            $key = array_search($userText, $productsNames);
            if (isset($productsNames[$key])) {
                $product = Product::where('name', $productsNames[$key])->first();
                $this->bot->setProductId($product->id);
                $this->afterSelectedProduct();
                exit;
            }
        }

        if (empty($productsNames)) {
            $this->bot->sendText('Препаратів не знайдено виберіть іншу проблему');
            $this->sendTextProblem();
        } else {
            if (count($productsNames) === 1) {
                $product = Product::where('name', $productsNames[0])->first();
                $this->bot->setProductId($product->id);
                $this->bot->sendText('Для вирішення даної проблему найдено тільки один препарат: ' . $product->name);
                $this->afterSelectedProduct();
                exit;
            }
            $this->bot->setKeyboard($productsNames);
            $this->bot->setText('Для вирішення вашої проблеми підходять такі препарати');
            $this->bot->send(BaseBot::KEYBOARD);
            exit;
        }
    }
}