<?php

namespace App\Service\BaseBot;


use App\Service\BaseBot\Logic\Logic;
use Illuminate\Support\Facades\Cache;

class BaseBot
{
    public $currentMethod;
    public $currentFlow;
    public $cacheId;

    protected $typeBot;
    protected $id;
    protected $userText;
    protected $problemGroupId;
    protected $problemId;
    protected $brandId;
    protected $productId;
    protected $cultureId;

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
        $this->currentMethod = Logic::METHOD_WELCOME;
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

    public function sendText($text)
    {
        $this->text = $text;
        $this->send(self::TEXT);
    }




    public function runMethod()
    {
        $logic = new Logic($this);
        $logic->runMethod();
    }


    public function getProblemGroupId()
    {
        return $this->problemGroupId;
    }





    public function getUserText()
    {
        return $this->userText;
    }

    public function setUserText($userText): void
    {
        $this->userText = $userText;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKeyboard()
    {
        return $this->keyboard;
    }


    public function getCultureId()
    {
        return $this->cultureId;
    }

    public function setProblemGroupId($problemGroupId): void
    {
        $this->problemGroupId = $problemGroupId;
        $this->saveCache();
    }

    public function setCultureId($cultureId): void
    {
        $this->cultureId = $cultureId;
        $this->saveCache();
    }

    public function setText($text): void
    {
        $this->text = $text;
        $this->saveCache();
    }

    public function setKeyboard($keyboard): void
    {
        $this->keyboard = $keyboard;
        $this->saveCache();
    }

    public function setCurrentMethod(string $currentMethod): void
    {
        $this->currentMethod = $currentMethod;
        $this->saveCache();
    }

    public function setCurrentFlow(string $currentFlow): void
    {
        $this->currentFlow = $currentFlow;
        $this->saveCache();
    }

    public function saveCache()
    {
        Cache::put($this->cacheId, $this, self::TIME_CACHE);
    }



}