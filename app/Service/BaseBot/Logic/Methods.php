<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/7/19
 * Time: 3:32 PM
 */

namespace App\Service\BaseBot\Logic;


use App\BaseModels\Culture;
use App\Service\BaseBot\BaseBot;
use Illuminate\Support\Facades\Cache;

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

        $this->welcome();
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
            $this->selectCulture();
            exit;
        }

        if (Culture::where('name', 'LIKE', "%{$this->bot->getUserText()}%")->count() === 0) {
            $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_CULTURE);
            $this->bot->sendText('METHOD_SEND_TEXT_CULTURE');
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

        //        Cache::put('webBot', $cultureNames, BaseBot::TIME_CACHE);
    }


    public function selectCulture()
    {
        $this->bot->sendText('RUN selectCulture');
        $this->bot->setCultureId(Culture::where('name', $this->bot->getUserText())->value('id'));
        $this->bot->setCurrentMethod(Logic::METHOD_SELECT_CULTURE);
        $this->nextMethod();
        $this->runMethod();

    }

}