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
        $this->bot->sendText('Введіть назву культури або перші букви');
        $this->nextMethod();
    }

    public function searchCulture()
    {
        if (Culture::where('name', $this->bot->getUserText())->count() === 1) {
            $this->bot->setCurrentMethod(Logic::METHOD_SELECT_CULTURE);
            $this->bot->sendText('method: METHOD_SELECT_CULTURE');
            exit;
        }

        if (Culture::where('name', 'LIKE', "%{$this->bot->getUserText()}%")->count() === 0) {
            $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_CULTURE);
            $this->bot->sendText('method: METHOD_SELECT_CULTURE');
            exit;
        }

        $this->bot->setText('Виберіть із списка одну культуру яка вам найбільше підходить');
        $this->bot->setKeyboard(
            Culture::where('name', 'LIKE', "%{$this->bot->getUserText()}%")
                ->pluck('name')
                ->toArray()
        );
        $this->bot->send(BaseBot::KEYBOARD);
    }


    public function selectCulture()
    {
//        $data = Cache::get($chatId);
//        $data['method'] = 'selectCulture';
//        $data['culture_id'] = Culture::where('name', $text)->value('id');
//        Cache::put($chatId, $data, self::TIME_CACHE);
//        $this->sendTextProblemGroup($chatId, $text);
    }

}