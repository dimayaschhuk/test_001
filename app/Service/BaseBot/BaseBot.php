<?php

namespace App\Service\BaseBot;


use App\Service\BaseBot\Logic\Logic;
use Illuminate\Support\Facades\Cache;

class BaseBot
{
    public $currentMethod;
    public $currentFlow;
    public $cacheId;


    protected $currentPageProductGroup = 1;
    protected $currentPageProduct = 1;


    protected $typeBot;
    protected $id;
    protected $userText;
    protected $problemGroupId;
    protected $problemId;
    protected $brandId;
    protected $productId;
    protected $productGroupId;
    protected $cultureId;

    protected $text;
    protected $keyboard;


    const KEYBOARD = 'keyboard';
    const TEXT = 'text';
    const TYPE_VIBER = 'VIBER';
    const TYPE_TELGRAM = "TELEGRAM";
    const TYPE_FB = "FB";
    const FIRST_METHOD = "welcome";
    const TIME_CACHE = 60;
    const BUTTON_GO_BACK = 'Повернутися назад';


    public function __construct($typeBot, $id)
    {
        $this->typeBot = $typeBot;
        $this->id = $id;
        $this->cacheId = $typeBot . "/" . $id;
        $this->currentMethod = Logic::METHOD_WELCOME;
    }

    public function send($typeMessage)
    {

        if ($typeMessage == self::TEXT) {
            $this->setKeyboard([]);
        }
        send_keyboard($this->typeBot, $this);
//        if ($typeMessage == self::KEYBOARD) {
//            send_keyboard($this->typeBot, $this);
//        }
//
//        if ($typeMessage == self::TEXT) {
//            send_text($this->typeBot, $this);
//        }

    }

    public function sendText($text)
    {
        $this->text = $text;
        send_text($this->typeBot, $this);
    }


    public function runMethod()
    {
        $logic = new Logic($this);
        $logic->runMethod();
    }


    public function getProblemId()
    {
        return $this->problemId;
    }


    public function getProductId()
    {
        return $this->productId;
    }


    public function getProblemGroupId()
    {
        return $this->problemGroupId;
    }


    public function getUserText()
    {
        return $this->userText;
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
        if (!in_array(self::BUTTON_GO_BACK, $this->keyboard)) {
            $this->keyboard[] = self::BUTTON_GO_BACK;
        }
        return $this->keyboard;
    }


    public function getCultureId()
    {
        return $this->cultureId;
    }


    public function getCurrentPageProductGroup(): int
    {
        return $this->currentPageProductGroup;
    }


    public function getProductGroupId()
    {
        return $this->productGroupId;
    }


    public function getCurrentPageProduct(): int
    {
        return $this->currentPageProduct;
    }


    public function getBrandId()
    {
        return $this->brandId;
    }


    public function setBrandId($brandId): void
    {
        $this->brandId = $brandId;
        $this->saveCache();
    }

    public function getCurrentFlow()
    {
        return $this->currentFlow;
    }

    public function setCurrentPageProduct(int $currentPageProduct): void
    {
        $this->currentPageProduct = $currentPageProduct;
        $this->saveCache();
    }


    public function setProductGroupId($productGroupId): void
    {
        $this->productGroupId = $productGroupId;
        $this->saveCache();
    }


    public function setCurrentPageProductGroup(int $currentPageProductGroup): void
    {
        $this->currentPageProductGroup = $currentPageProductGroup;
        $this->saveCache();
    }

    public function setUserText($userText): void
    {
        $this->userText = $userText;
        $this->saveCache();

    }

    public function setProductId($productId): void
    {
        $this->productId = $productId;
        $this->saveCache();
    }

    public function setProblemId($probId): void
    {
        $this->problemId = $probId;
        $this->saveCache();
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