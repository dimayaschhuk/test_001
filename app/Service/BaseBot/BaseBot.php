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


    public function runMethod()
    {
        $logic = new Logic($this);
        $logic->runMethod();
    }


    public function setCurrentMethod(string $currentMethod): void
    {
        $this->currentMethod = $currentMethod;
        Cache::put($this->id, $this, self::TIME_CACHE);
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

    public function setCurrentFlow(string $currentFlow): void
    {
        $this->text = 'setCurrentFlow' . $currentFlow;
        $this->send(self::TEXT);
        $this->currentFlow = $currentFlow;
    }

    public function saveCache(){
        Cache::put($this->cacheId, $this, self::TIME_CACHE);
    }

}