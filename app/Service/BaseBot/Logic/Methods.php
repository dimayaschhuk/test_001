<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/7/19
 * Time: 3:32 PM
 */

namespace App\Service\BaseBot\Logic;


use App\BaseModels\Culture;
use App\BaseModels\Problem;
use App\BaseModels\ProblemGroup;
use App\BaseModels\Product;
use App\Service\BaseBot\BaseBot;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
            $this->bot->setCultureId(Culture::where('name', $this->bot->getUserText())->value('id'));
            $this->sendTextProblemGroup();
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

    }

    public function sendTextProblemGroup()
    {
        $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_PROBLEM_GROUP);
        $culture = Culture::find($this->bot->getCultureId());


        if ($culture->checkProblem($this->bot->getUserText())) {
            $this->bot->setProblemId(Problem::where('name', $this->bot->getUserText())->first()->id);
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

        if ($culture->checkProblem($this->bot->getUserText())) {
            $this->bot->setProblemId(Problem::where('name', $this->bot->getUserText())->first()->id);
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

            $this->bot->sentText('До даної культури немає проблем');
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
        $culture = Culture::find($this->bot->getCultureId());

        if ($culture->checkProduct($this->bot->getUserText(), $this->bot->getProblemId())) {

            exit;
        }

        if (empty($culture->getProductsNames($this->bot->getProblemId()))) {
            $this->bot->sendText('Препаратів не знайдено виберіть іншу проблему');
            $this->sendTextProblem();
        } else {
            $this->bot->sendText('else');
//            if (count($culture->getProductsNames($this->bot->getProblemId())) === 1) {
//                $this->bot->setProductId(Product::where('name',$culture->getProductsNames($this->bot->getProblemId())));
//                $test['productName'] = $culture->getProductsNames($data['problem_id'])[0];
//                Cache::put('test', $test, self::TIME_CACHE);
//                send_text($chatId,
//                    'Для вирішення даної проблему найдено тільки один препарат: ');
//                $this->selectProduct($chatId, $text);
//                exit;
//            }
//            $keyboard = get_keyboard($culture->getProductsNames($data['problem_id']));
//            send_keyboard($chatId, $keyboard, 'Для вирішення вашої проблеми підходять такі препарати');
        }
    }
}