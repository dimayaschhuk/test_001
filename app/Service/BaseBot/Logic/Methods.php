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


    public function selectFlow()
    {
        $flow = $this->bot->getUserText();
        if ($flow == self::FLOW_PROTECT_CULTURE_UA) {
            $this->bot->setCurrentFlow(self::FLOW_PROTECT_CULTURE);
            $this->nextMethod();
            $this->runMethod();
            exit;
        }

        if ($flow == self::FLOW_PRODUCT_UA) {
            $this->bot->setCurrentFlow(self::FLOW_PRODUCT);
            $this->nextMethod();
            $this->runMethod();
            exit;
        }

        $this->welcome();
        exit;
    }


    public function afterSelectedProduct()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_AFTER_SELECTED_PRODUCT);
        $text = $this->bot->getUserText();
        if ($text === Logic::APPLICATION_CULTURE) {
            $this->applicationCulture();
            exit;
        }
        if ($text === Logic::PROBLEN) {
            $this->problem();
            exit;
        }
        if ($text === Logic::LEARN_MORE) {
            $this->learnMore();
            exit;
        }
        if ($text === Logic::DESCRIPTION) {
            $this->description();
            exit;
        }
        if ($text === Logic::PRICE) {
            $this->price();
            exit;
        }
        $this->bot->setText('Що саме вас цікавить?');
        $this->bot->setKeyboard([
            'Застосування на культурі',
            'Проблематика',
            'Дізнатися більше',
            'Опис',
            'Ціни і наявність',
        ]);
    }

    public function applicationCulture()
    {
        $this->bot->sendText('applicationCulture');
    }

    public function problem()
    {
        $this->bot->sendText('problem');
    }

    public function learnMore()
    {
        $this->bot->sendText('learnMore');
    }

    public function description()
    {
        $this->bot->sendText('description');
    }

    public function price()
    {
        $this->bot->sendText('price');
    }


}