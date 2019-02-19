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
use App\BaseModels\Product;
use App\BaseModels\Technology;
use App\Service\BaseBot\BaseBot;
use Illuminate\Support\Facades\DB;

trait Methods
{

    public function goBack()
    {
        $this->bot->setText("Куди саме ви хочете повернутися");
        $keyboard = ['Виір гілки'];
        if ($this->bot->getCurrentFlow()) {
            $keyboard = array_merge($keyboard, $this->getMethod()[$this->bot->getCurrentFlow()]);
        }
        $this->bot->setKeyboard($keyboard);
        $this->bot->send(BaseBot::KEYBOARD);
        exit;
    }

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
        $this->bot->send(BaseBot::KEYBOARD);
    }

    public function applicationCulture()
    {
        $text = $this->bot->getUserText();
        if ($this->bot->getCultureId()) {
            $this->bot->setCurrentMethod(Logic::APPLICATION_CULTURE);
            if ($text != 'По всіх культурах') {
                $this->getApplicationCulture();
                exit;
            }
            if ($text != 'Вибраній культурі') {
                $this->getApplicationCulture(TRUE);
                exit;
            }
            $this->bot->setText('Вас цікавить?');
            $this->bot->setKeyboard([
                'По всіх культурах',
                'Вибраній культурі',
            ]);
            $this->bot->send(BaseBot::KEYBOARD);
        } else {
            $this->getApplicationCulture();
        }

    }

    public function getApplicationCulture($flag = FALSE)
    {

        if($flag){
            $technologIDs=DB::table('pd_CultureForCropProcessing')
                ->where('cultureId',$this->bot->getCultureId())->pluck('cropProcessingId')->toArray();
            $technologies = Technology::where('productId', $this->bot->getProductId())->whereIn('id',$technologIDs)->get();
        }else{
            $technologies = Technology::where('productId', $this->bot->getProductId())->get();
        }

        $text = [];
        foreach ($technologies as $technology) {
            $textTechnology = '';
            if (!$technology->isEmpty($technology->consumptionNormMin)) {
                $textTechnology .= 'Споживання min: ' . $technology->consumptionNormMin . ",\n";
            }
            if (!$technology->isEmpty($technology->consumptionNormMax)) {
                $textTechnology .= "Споживання max: " . $technology->consumptionNormMax . ",\n";
            }
            if (!$technology->isEmpty($technology->amountUnit) && !$technology->isEmpty($technology->areaUnit)) {
                $textTechnology .= "Одиниці: " . $technology->amountUnit . "/" . $technology->areaUnit . ",\n";
            }
            if (!$technology->isEmpty($technology->maxTreatmentCount)) {
                $textTechnology .= "max кіл. лікування: " . $technology->maxTreatmentCount . ",\n";
            }


            if (!$technology->isEmpty($technology->consumptionNormMinFluid)) {
                $textTechnology .= "Споживання min Fluid: " . $technology->consumptionNormMinFluid . ",\n";
            }

            if (!$technology->isEmpty($technology->consumptionNormMaxFluid)) {
                $textTechnology .= "Споживання max Fluid: " . $technology->consumptionNormMaxFluid . ",\n";
            }

            if (!$technology->isEmpty($technology->amountUnitFluid) &&
                !$technology->isEmpty($technology->areaUnitFluid)) {
                $textTechnology .=
                    "Одиниці Fluid: " . $technology->amountUnitFluid . "/" . $technology->areaUnitFluid . ",\n";
            }


            if (!$technology->isEmpty($technology->watingTime)) {
                $textTechnology .= "Час очікування: " . $technology->watingTime . ",\n";
            }

            if (!$technology->isEmpty($technology->watingTerms)) {
                $textTechnology .= "Умови : " . $technology->watingTerms . ",\n";
            }

            if (!$technology->isEmpty($technology->features)) {
                $textTechnology .= "Функции: " . $technology->features . ",\n";
            }

            $cultureId = \Illuminate\Support\Facades\DB::table('pd_CultureForCropProcessing')
                ->where('cropProcessingId', $technology->id)
                ->pluck('cultureId')
                ->toArray();
            $cultureNames = Culture::whereIn('id', $cultureId)
                ->pluck('name')
                ->toArray();
            $nameCulture = '';
            foreach ($cultureNames as $cultureName) {
                $nameCulture .= $cultureName . ',';
            }

            $problemId = \Illuminate\Support\Facades\DB::table('pd_VerminForCropProcessing')
                ->where('cropProcessingId', $technology->id)
                ->pluck('verminId')
                ->toArray();
            $problemNames = Problem::whereIn('id', $problemId)
                ->pluck('name')
                ->toArray();
            $problemName = '';
            foreach ($problemNames as $name) {
                $problemName .= $name . ',';
            }

            $text[] = ['technology' => $textTechnology, 'culture' => $nameCulture, 'problem' => $problemName];
        }

        foreach ($text as $item) {
            $this->bot->sendText('Культури:' . $item['culture']);
            $this->bot->sendText('Проблеми:' . $item['problem']);
            $this->bot->sendText('Застосування:' . $item['technology']);
        }
    }

    public function problem()
    {
        $this->bot->sendText('problem');
    }

    public function learnMore()
    {
        $this->bot->sendText('менеджер Іван Іванович Іванов');
        $this->bot->sendText('+38(095)22-22-2222');
        $this->bot->sendText('06877777777');
    }

    public function description()
    {
        $productDescription = Product::find($this->bot->getProductId())->shortDescription;
        $text = strip_tags($productDescription);
        if ($text != NULL && $text != '' && $text != ' ' && $text != '-') {
            $this->bot->sendText($text);
        } else {
            $this->bot->sendText("Опис не найдено");
        }


    }

    public function price()
    {
        $this->bot->sendText('бази з цінами поки немає');
    }


}