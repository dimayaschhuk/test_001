<?php

namespace App\Service\BaseBot;


use App\Service\BaseBot\Logic\Logic;
use Illuminate\Support\Facades\Cache;

class BaseBot
{
    protected $id;
    protected $cacheId;
    protected $typeBot;
    protected $currentMethod;
    protected $currentFlow;
    protected $userText;
    protected $viberBot;


    protected $problemGroupId;
    protected $problemId;
    protected $brandId;
    protected $productId;

    protected $text;
    protected $keyboard;

    const KEYBOARD = 'keyboard';
    const TEXT = 'text';
    const TYPE_VIBER = 'VIBER';
    const TYPE_TELGRAM = "TELEGRAM";
    const FIRST_METHOD = "welcome";
    const TIME_CACHE = 1;

    public function __construct($typeBot, $id)
    {
        $this->typeBot = $typeBot;
        $this->id = $id;
        $this->cacheId = $typeBot . "/" . $id;
        $this->currentMethod = Logic::METHOD_SEND_TEXT_CULTURE;
        $this->currentFlow = Logic::FLOW_PROTECT_CULTURE;

    }

    /**
     * @return string
     */
    public function getCurrentMethod(): string
    {
        return $this->currentMethod;
    }

    /**
     * @param string $currentMethod
     */
    public function setCurrentMethod(string $currentMethod): void
    {
        $this->currentMethod = $currentMethod;
        Cache::put($this->id, $this, self::TIME_CACHE);
    }

    public function getViberBot()
    {
        return $this->viberBot;
    }


    public function setViberBot($viberBot): void
    {
        $this->viberBot = $viberBot;
    }


    public function getUserText()
    {
        return $this->userText;
    }

    public function setUserText($userText): void
    {
        $this->userText = $userText;
    }

    public function setText($text): void
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setKeyboard($keyboard): void
    {
        $this->keyboard = $keyboard;
    }

    public function getKeyboard()
    {
        return $this->keyboard;
    }


    public function nextMethod()
    {
        $key = array_search($this->currentMethod, $this->getMethod()[$this->currentFlow]);
        $this->currentMethod = $this->getMethod()[$this->currentFlow][$key + 1];
        Cache::put($this->id, $this, self::TIME_CACHE);
    }

    public function getMethod()
    {
        return [
            Logic::METHOD_WELCOME,
            Logic::FLOW_PROTECT_CULTURE => [
                Logic::METHOD_SEND_TEXT_CULTURE,
                Logic::METHOD_SEARCH_CULTURE,
                Logic::METHOD_SELECT_CULTURE,
                Logic::METHOD_SEND_TEXT_PROBLEM_GROUP,
                Logic::METHOD_SELECT_PROBLEM_GROUP,
                Logic::METHOD_SEND_TEXT_PROBLEM,
                Logic::METHOD_SELECT_PROBLEM,
                Logic::METHOD_SEARCH_PRODUCT,
                Logic::METHOD_SELECT_PRODUCT,
            ],
        ];
    }

    public function runMethod()
    {
        $logic = new Logic($this);
        if ($this->currentFlow == Logic::FLOW_PROTECT_CULTURE) {

            switch ($this->currentMethod) {
                case Logic::METHOD_SEND_TEXT_CULTURE :
                    $logic->sendTextCulture();
                    break;

                case Logic::METHOD_SEARCH_CULTURE :
                    $logic->searchCulture();
                    break;


                case Logic::METHOD_SELECT_CULTURE :
                    $logic->selectCulture();
                    break;


                case Logic::METHOD_SEND_TEXT_PROBLEM_GROUP :
                    $logic->sendTextProblemGroup();
                    break;


                case Logic::METHOD_SELECT_PROBLEM_GROUP :
                    $logic->selectProblemGroup();
                    break;


                case Logic::METHOD_SEND_TEXT_PROBLEM :
                    $logic->sendTextProblem();
                    break;


            }
        }

//            if ( == Logic::METHOD_SEND_TEXT_CULTURE) {
//
//            }
//            if ($method == 'searchCulture') {
//                $this->searchCulture($chatId, $text);
//            }
//            if ($method == 'selectCulture') {
//                $this->selectCulture($chatId, $text);
//            }
//
//
//            if ($method == 'sendTextProblemGroup') {
//                $this->sendTextProblemGroup($chatId, $text);
//            }
//            if ($method == 'selectProblemGroup') {
//                $this->selectProblemGroup($chatId, $text);
//            }
//
//            if ($method == 'sendTextProblem') {
//                $this->sendTextProblem($chatId, $text);
//            }
//            if ($method == 'searchProblem') {
//                $this->searchProblem($chatId, $text);
//            }
//            if ($method == 'selectProblem') {
//                $this->selectProblem($chatId, $text);
//            }
//
//            if ($method == 'searchProduct') {
//                $this->searchProduct($chatId, $text);
//            }
//            if ($method == 'selectProduct') {
//                $this->selectProduct($chatId, $text);
//            }
//
//        } else {
//            $this->test($chatId, 'not testFlow');
//        }
    }


    public function send($typeMessage)
    {

        if ($typeMessage == self::KEYBOARD) {
            send_keyboard($this->typeBot, $this);
        }

        if ($typeMessage == self::TEXT) {
            send_text($this->typeBot, $this);
        }
    }


}