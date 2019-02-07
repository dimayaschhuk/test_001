<?php

namespace App\Service\BaseBot;


use App\Service\BaseBot\Logic\Logic;
use Illuminate\Support\Facades\Cache;

class BaseBot
{
    protected $id;
    protected $cacheId;
    protected $typeBot;
    public $currentMethod;
    public $currentFlow;
    protected $userText;
    protected $viberBot;


    protected $problemGroupId;
    protected $problemId;
    protected $brandId;
    protected $productId;

    protected $text;
    protected $keyboard;

    protected $statusSendMessage = FALSE;


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
//        $this->currentFlow = Logic::FLOW_PROTECT_CULTURE;
    }

    public function send($typeMessage)
    {
        if ($this->statusSendMessage) {
            if ($typeMessage == self::KEYBOARD) {
                send_keyboard($this->typeBot, $this);
            }

            if ($typeMessage == self::TEXT) {
                send_text($this->typeBot, $this);
            }
            $this->statusSendMessage = FALSE;
        }


    }



    public function runMethod()
    {
        $logic = new Logic($this);
        $logic->runMethod();
    }




    public function isStatusSendMessage(): bool
    {
        return $this->statusSendMessage;
    }

    public function setStatusSendMessage(bool $statusSendMessage): void
    {
        $this->statusSendMessage = $statusSendMessage;
    }

    public function getCurrentMethod(): string
    {
        return $this->currentMethod;
    }

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
        if ($this->userText != $userText) {
            $this->statusSendMessage = TRUE;
        }
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


    public function getCurrentFlow(): string
    {
        return $this->currentFlow;
    }


    public function setCurrentFlow(string $currentFlow): void
    {
        $this->currentFlow = $currentFlow;
    }

}