<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/6/19
 * Time: 3:13 PM
 */

namespace App\Service\BaseBot\Logic;


use App\BaseModels\Culture;
use App\Service\BaseBot\BaseBot;
use Illuminate\Support\Facades\Cache;

class Logic
{
    protected $bot;

    const METHOD_WELCOME = 'welcome';
    const METHOD_SELECT_FLOW = 'selectFlow';
    const METHOD_SEND_TEXT_CULTURE = 'sendTextCulture';
    const METHOD_SEARCH_CULTURE = 'searchCulture';
    const METHOD_SELECT_CULTURE = 'selectCulture';
    const METHOD_SEND_TEXT_PROBLEM_GROUP = 'sendTextProblemGroup';
    const METHOD_SELECT_PROBLEM_GROUP = 'selectProblemGroup';
    const METHOD_SEND_TEXT_PROBLEM = 'sendTextProblem';
    const METHOD_SELECT_PROBLEM = 'selectProblem';
    const METHOD_SEARCH_PRODUCT = 'searchProduct';
    const METHOD_SELECT_PRODUCT = 'selectProduct';

    const FLOW_PROTECT_CULTURE = 'protectCulture';

    const FLOW_PROTECT_CULTURE_UA = "Захист культури";




    public function __construct(BaseBot $baseBot)
    {
        $this->bot = $baseBot;
    }


    public function runMethod()
    {

        if ($this->bot->currentMethod == self::METHOD_WELCOME) {
            $this->welcome();
        }

        if ($this->bot->currentMethod == self::METHOD_SELECT_FLOW) {
            $this->selectFlow();
        }


        if ($this->bot->currentFlow == self::FLOW_PROTECT_CULTURE) {

            if ($this->bot->currentMethod == self::METHOD_SEND_TEXT_CULTURE) {
                $this->sendTextCulture();
            }
//            switch () {
//                case  :
//
//                    break;
//
//                case self::METHOD_SEARCH_CULTURE :
//                    $this->searchCulture();
//                    break;
//
//
//                case self::METHOD_SELECT_CULTURE :
//                    $this->selectCulture();
//                    break;
//
//
//                case self::METHOD_SEND_TEXT_PROBLEM_GROUP :
//                    $this->sendTextProblemGroup();
//                    break;
//
//
//                case self::METHOD_SELECT_PROBLEM_GROUP :
//                    $this->selectProblemGroup();
//                    break;
//
//
//                case self::METHOD_SEND_TEXT_PROBLEM :
//                    $this->sendTextProblem();
//                    break;
//
//
//            }
        }
    }


    public function welcome()
    {
        $this->bot->setText("Виберіть гілку");
        $this->bot->setKeyboard(['Захист культури', 'Продукти']);
        $this->bot->send(BaseBot::KEYBOARD);
        $this->bot->setCurrentMethod(self::METHOD_SELECT_FLOW);
        Cache::put($this->bot->cacheId, $this->bot, BaseBot::TIME_CACHE);
        exit;
    }

    public function selectFlow()
    {

        $flow = $this->bot->getUserText();
        if ($flow == self::FLOW_PROTECT_CULTURE_UA) {
            $this->bot->setText("true");
            $this->bot->send(BaseBot::TEXT);
            $this->bot->setCurrentFlow(self::FLOW_PROTECT_CULTURE);
            $this->nextMethod();
            exit;
        }

        $this->welcome();
    }

    public function sendTextCulture()
    {
        $this->bot->setText('Введіть назву культури або перші букви');
        $this->bot->send(BaseBot::TEXT);
        $this->bot->nextMethod();
    }

    public function searchCulture()
    {
        if (Culture::where('name', $this->bot->getUserText())->count() === 1) {
            $this->bot->setCurrentMethod(Logic::METHOD_SELECT_CULTURE);
            $this->bot->runMethod();
            exit;
        }

        if (Culture::where('name', 'LIKE', "%{$this->bot->getUserText()}%")->count() === 0) {
            $this->bot->setCurrentMethod(Logic::METHOD_SEND_TEXT_CULTURE);
            $this->bot->runMethod();
            exit;
        }

        $this->bot->setText('Виберіть із списка одну культуру яка вам найбільше підходить');
        $this->bot->setKeyboard(Culture::where('name', 'LIKE',
            "%{$this->bot->getUserText()}%")->pluck('name')->toArray());
        $this->bot->send(BaseBot::KEYBOARD);
    }



    public function getMethod()
    {
        return [
            self::METHOD_WELCOME,
            self::METHOD_SELECT_FLOW,
            self::FLOW_PROTECT_CULTURE => [
                self::METHOD_SEND_TEXT_CULTURE,
                self::METHOD_SEARCH_CULTURE,
                self::METHOD_SELECT_CULTURE,
                self::METHOD_SEND_TEXT_PROBLEM_GROUP,
                self::METHOD_SELECT_PROBLEM_GROUP,
                self::METHOD_SEND_TEXT_PROBLEM,
                self::METHOD_SELECT_PROBLEM,
                self::METHOD_SEARCH_PRODUCT,
                self::METHOD_SELECT_PRODUCT,
            ],
        ];
    }

    public function nextMethod()
    {
        $this->bot->setText("nextMethod");
        $this->bot->send(BaseBot::TEXT);
        if (isset($this->bot->currentFlow) && isset($this->bot->currentMethod)) {
            $this->bot->setText("nextMethod true");
            $this->bot->send(BaseBot::TEXT);
            $this->bot->currentMethod = $this->getMethod()[$this->currentFlow][0];
        }
        $this->bot->setText("false");
        $this->bot->send(BaseBot::TEXT);
        if(isset($baseBot->currentFlow) && isset($baseBot->currentMethod)){
            $key = array_search($this->bot->currentFlow, $this->getMethod()[$this->bot->currentFlow]);
            $this->bot->currentMethod = $this->getMethod()[$this->bot->currentFlow][$key + 1];
        }

        Cache::put($this->bot->cacheId, $this->bot, BaseBot::TIME_CACHE);
    }
//    public function selectCulture()
//    {
//        $data = Cache::get($chatId);
//        $data['method'] = 'selectCulture';
//        $data['culture_id'] = Culture::where('name', $text)->value('id');
//        Cache::put($chatId, $data, self::TIME_CACHE);
//        $this->sendTextProblemGroup($chatId, $text);
//    }
//
//
//    public function sendTextProblemGroup()
//    {
//        $data = Cache::get($chatId);
//        $culture = Culture::find($data['culture_id']);
//
//
//        if ($culture->checkProblem($text)) {
//            $this->selectProblem($chatId, $text);
//            exit;
//        }
//
//
//        if ($culture->checkProblemGroup($text)) {
//            $this->selectProblemGroup($chatId, $text);
//            exit;
//        }
//
////        if ($culture->checkLIKEProblem($text)) {
////            $this->searchProblem($chatId, $text);
////            exit;
////        }
//
//        if (empty($culture->getProblemGroupNames())) {
//            send_text($chatId, 'До даної культури немає продуктів');
//            $data = Cache::get($chatId);
//            $data['method'] = 'welcome';
//            Cache::put($chatId, $data, self::TIME_CACHE);
//            $this->sendTextCulture($chatId, $text);
//        } else {
//            $keyboard = get_keyboard($culture->getProblemGroupNames());
//            send_keyboard($chatId, $keyboard,
//                'Виберіть із списка групу в яку входить ваша проблема');
//        }
//
//    }
//
//    public function selectProblemGroup()
//    {
//        $data = Cache::get($chatId);
//        $data['method'] = 'selectProblemGroup';
//        $data['problemGroup_id'] = ProblemGroup::where('name', $text)->value('id');
//        Cache::put($chatId, $data, self::TIME_CACHE);
//        $this->sendTextProblem($chatId, $text);
//    }
//
//    public function sendTextProblem()
//    {
//        $data = Cache::get($chatId);
//        $culture = Culture::find($data['culture_id']);
//
//        if ($culture->checkProblem($text)) {
//            $this->selectProblem($chatId, $text);
//            exit;
//        }
//
////        if ($culture->checkLIKEProblem($text)) {
////            $this->searchProblem($chatId, $text);
////            exit;
////        }
//
//
//        if (empty($culture->getProblemNames($data['problemGroup_id']))) {
//            if ($culture->getProblemNames()) {
//                send_text($chatId, 'До даної культури немає проблем');
//                $data = Cache::get($chatId);
//                $data['method'] = 'welcome';
//                Cache::put($chatId, $data, self::TIME_CACHE);
//                $this->sendTextCulture($chatId, $text);
//                exit;
//            }
//            send_text($chatId, 'До даної культури немає проблем');
//            $this->sendTextProblemGroup($chatId, $text);
//            exit;
//        } else {
//            $keyboard = get_keyboard($culture->getProblemNames($data['problemGroup_id']));
//            send_keyboard($chatId, $keyboard, 'Виберіть назву проблеми');
//        }
//
//
//    }
//
//    public function searchProblem($chatId, $text)
//    {
//        $data = Cache::get($chatId);
//        $culture = Culture::find($data['culture_id']);
//
//        $keyboard = get_keyboard($culture->getLIKEProblemNames($text));
//        send_keyboard($chatId, $keyboard, 'Виберіть із списка проблему яка вам підходить');
//    }
//
//    public function selectProblem($chatId, $text)
//    {
//        $data = Cache::get($chatId);
//        $data['method'] = 'selectProblem';
//        $data['problem_id'] = Problem::where('name', $text)->value('id');
//        Cache::put($chatId, $data, self::TIME_CACHE);
//        $this->searchProduct($chatId, $text);
//    }
//
//    public function searchProduct($chatId, $text)
//    {
//        $data = Cache::get($chatId);
//        $culture = Culture::find($data['culture_id']);
//
//        if ($culture->checkProduct($text, $data['problem_id'])) {
//            $this->selectProduct($chatId, $text);
//            exit;
//        }
//
//        if (empty($culture->getProductsNames($data['problem_id']))) {
//            send_text($chatId, 'Препаратів не знайдено виберіть іншу проблему');
//            $this->sendTextProblem($chatId, $text);
//        } else {
//            if (count($culture->getProductsNames($data['problem_id'])) === 1) {
//                $test['$culture->getProductsNames($data[problem_id])'] = $culture->getProductsNames($data['problem_id']);
//                $test['productName'] = $culture->getProductsNames($data['problem_id'])[0];
//                Cache::put('test', $test, self::TIME_CACHE);
//                send_text($chatId,
//                    'Для вирішення даної проблему найдено тільки один препарат: ');
//                $this->selectProduct($chatId, $text);
//                exit;
//            }
//            $keyboard = get_keyboard($culture->getProductsNames($data['problem_id']));
//            send_keyboard($chatId, $keyboard, 'Для вирішення вашої проблеми підходять такі препарати');
//        }
//    }
//
//    public function selectProduct($chatId, $text)
//    {
//        $data = Cache::get($chatId);
//        $data['method'] = 'selectProduct';
//        $data['product_id'] = Product::where('name', $text)->value('id');
//        Cache::put($chatId, $data, self::TIME_CACHE);
//        $keyboard = [
//            ['Застосування на культурі', 'Проблематика'],
//            ['Дізнатися більше', 'Опис', 'Ціни і наявність'],
//        ];
//        send_keyboard($chatId, $keyboard, 'Що саме вас цікавить?');
//    }
//
//    public function sendTextProduct($chatId, $text)
//    {
//        if ($text == 'Применение на культр') {
//            $this->applicationToCulture($chatId, $text);
//            exit;
//        }
//
//        if ($text == 'Проблематика') {
//            $this->getProblem($chatId, $text);
//            exit;
//        }
//
//        if ($text == 'Описание') {
//            $this->getDescription($chatId, $text);
//            exit;
//        }
//
//        if ($text == 'Цены и наличие') {
//            $this->getPrice($chatId, $text);
//            exit;
//        }
//
//        if ($text == 'Узнать больше') {
//            exit;
//        }
//
//
//        $keyboard = [
//            ['Применение на культр', 'Проблематика'],
//            ['Узнать больше', 'Описание', 'Цены и наличие'],
//        ];
//        send_keyboard($chatId, $keyboard, 'Виберіть що саме вас цікавить?');
//    }
//
//    public function applicationToCulture($chatId, $text)
//    {
//        $this->test($chatId, 'applicationToCulture');
//        $data = Cache::get($chatId);
//        $pd_CultureForCropProcessing = DB::where('cultureId', $data['culture_id'])
//            ->pluck('cropProcessingId')
//            ->toArray();
//        $pd_VerminForCropProcessing = DB::where('verminId', $data['product_id'])
//            ->pluck('cropProcessingId')
//            ->toArray();
//        $technologyIds = array_intersect($pd_CultureForCropProcessing, $pd_VerminForCropProcessing);
//        $technology = Technology::find($technologyIds[0]);
//        $text = "consumptionNormMin:" . $technology->consumptionNormMin . ", consumptionNormMax: " .
//            $technology->consumptionNormMax . ", lastTreatmentTerm: " . $technology->lastTreatmentTerm .
//            ", maxTreatmentCount: " . $technology->maxTreatmentCount . ", experience:" . $technology->experience .
//            ", areaUnit:" . $technology->areaUnit . ", amountUnit:" . $technology->amountUnit;
//        Cache::put('test', $text, 1);
//        send_text($chatId, $text);
//        $keyboard = [
//            ['Применение на культр', 'Проблематика'],
//            ['Узнать больше', 'Описание', 'Цены и наличие'],
//        ];
//        send_keyboard($chatId, $keyboard, 'Щe щось?');
//
//    }
//
//    public function getProblem($chatId, $text)
//    {
//
//    }
//
//    public function getDescription($chatId, $text)
//    {
//
//    }
//
//    public function getPrice($chatId, $text)
//    {
//
//    }


}