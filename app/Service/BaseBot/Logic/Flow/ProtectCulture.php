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
    public function welcome()
    {
        $this->bot->setText("Виберіть гілку");
        $this->bot->setKeyboard(['Захист культури', 'Продукти']);
        $this->bot->send(BaseBot::KEYBOARD);
        $this->bot->setCurrentMethod(self::METHOD_SELECT_FLOW);
        exit;
    }

    public function selectFlow()
    {
        $flow = $this->bot->getUserText();
        if ($flow == self::FLOW_PROTECT_CULTURE_UA) {
            $this->bot->setCurrentFlow(self::FLOW_PROTECT_CULTURE);
            $this->nextMethod();
            $this->runMethod();
            exit;
        }

        $this->welcome();
        exit;
    }

    public function sendTextCulture()
    {
        $this->bot->sendText('Введіть назву культури або перші 3 букви');
        $this->nextMethod();
        exit;
    }

    public function searchCulture()
    {
        if (Culture::where('name', $this->bot->getUserText())->count() === 1) {
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

    }

//==================================================


    public function sendTextProblemGroup()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_PROBLEM_GROUP);
        $culture = Culture::find($this->bot->getCultureId());

        if ($culture->checkProblem($this->bot->getUserText())) {
            $this->sendTextProblem();
            exit;
        }

        if ($culture->checkProblemGroup($this->bot->getUserText())) {
            $this->bot->setProblemGroupId(ProblemGroup::where('name', $this->bot->getUserText())->first()->id);
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

    }

    public function sendTextProblem()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_PROBLEM);
        $culture = Culture::find($this->bot->getCultureId());
        $userText = $this->bot->getUserText();

        if ($culture->checkProblem($userText)) {
            $this->sendText('dddd');
            $this->bot->setProblemId(Problem::where('name', $userText)->first()->id);
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
            $this->bot->sentText('До даної культури не знайдено проблеми з цієї групи проблем');
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
        $this->sendText('sssss');
        $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_PRODUCT);
        $culture = Culture::find($this->bot->getCultureId());
        $productsNames = $culture->getProductsNames($this->bot->getProblemId());

        if ($culture->checkProduct($this->bot->getUserText(), $this->bot->getProblemId())) {
            $key = array_search($this->bot->getUserText(), $productsNames);
            if (isset($productsNames[$key])) {
                $product = Product::where('name', $productsNames[$key])->first();
                $this->bot->setProductId($product->id);
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
                exit;
            }
            $this->bot->setKeyboard($productsNames);
            $this->bot->setText('Для вирішення вашої проблеми підходять такі препарати');
            $this->bot->send(BaseBot::KEYBOARD);
            exit;
        }
    }
}