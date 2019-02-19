<?php
/**
 * Created by PhpStorm.
 * User: dmytro
 * Date: 2/7/19
 * Time: 3:32 PM
 */

namespace App\Service\BaseBot\Logic;


use App\BaseModels\Culture;
use App\BaseModels\Product;
use App\BaseModels\Technology;
use App\Service\BaseBot\BaseBot;

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

        if ($this->bot->getCultureId()) {
            $this->bot->setText('Вас цікавить?');
            $this->bot->setKeyboard([
                'По всіх культурах',
                'Вибраній культурі',
            ]);
            $this->bot->send(BaseBot::KEYBOARD);
        } else {
            $technologies = Technology::where('productId', $this->bot->getProductId())->get();
            $text = [];
            foreach ($technologies as $technology) {
                $textTechnology = '';
                if ($technology->consumptionNormMin != NULL &&
                    $technology->consumptionNormMin != '' &&
                    $technology->consumptionNormMin != ' ' &&
                    $technology->consumptionNormMin != '-') {
                    $textTechnology.= 'Споживання min: ' . $technology->consumptionNormMin . ",\n";
                }
                if ($technology->consumptionNormMax != NULL &&
                    $technology->consumptionNormMax != '' &&
                    $technology->consumptionNormMax != ' ' &&
                    $technology->consumptionNormMax != '-') {
                    $textTechnology.= "Споживання max: " . $technology->consumptionNormMax . ",\n";
                }

                if ($technology->maxTreatmentCount != NULL &&
                    $technology->maxTreatmentCount != '' &&
                    $technology->maxTreatmentCount != ' ' &&
                    $technology->maxTreatmentCount != '-') {
                    $textTechnology.= "max кіл. лікування: " . $technology->maxTreatmentCount . ",\n";
                }
                if ($technology->amountUnit != NULL &&
                    $technology->amountUnit != '' &&
                    $technology->amountUnit != ' ' &&
                    $technology->amountUnit != '-') {
                    $textTechnology.= "Кількість одиниці: " . $technology->amountUnit . ",\n";
                }
                if ($technology->areaUnit != NULL &&
                    $technology->areaUnit != '' &&
                    $technology->areaUnit != ' ' &&
                    $technology->areaUnit != '-') {
                    $textTechnology.= "Площа одиниці: " . $technology->areaUnit . ",\n";
                }

                if ($technology->consumptionNormMinFluid != NULL &&
                    $technology->consumptionNormMinFluid != '' &&
                    $technology->consumptionNormMinFluid != ' ' &&
                    $technology->consumptionNormMinFluid != '-') {
                    $textTechnology.= "Споживання min Fluid: " . $technology->consumptionNormMinFluid . ",\n";
                }

                if ($technology->consumptionNormMaxFluid != NULL &&
                    $technology->consumptionNormMaxFluid != '' &&
                    $technology->consumptionNormMaxFluid != ' ' &&
                    $technology->consumptionNormMaxFluid != '-') {
                    $textTechnology.= "Споживання max Fluid: " . $technology->consumptionNormMaxFluid . ",\n";
                }
                if ($technology->amountUnitFluid != NULL &&
                    $technology->amountUnitFluid != '' &&
                    $technology->amountUnitFluid != ' ' &&
                    $technology->amountUnitFluid != '-') {
                    $textTechnology.= "Кількість одиниці Fluid: " . $technology->amountUnitFluid . ",\n";
                }

                if ($technology->areaUnitFluid != NULL &&
                    $technology->areaUnitFluid != '' &&
                    $technology->areaUnitFluid != ' ' &&
                    $technology->areaUnitFluid != '-') {
                    $textTechnology.= "Площа одиниці Fluid: " . $technology->areaUnitFluid . ",\n";
                }

                if ($technology->watingTime != NULL &&
                    $technology->watingTime != '' &&
                    $technology->watingTime != ' ' &&
                    $technology->watingTime != '-') {
                    $textTechnology.= "Час очікування: " . $technology->watingTime . ",\n";
                }

                if ($technology->watingTerms != NULL &&
                    $technology->watingTerms != '' &&
                    $technology->watingTerms != ' ' &&
                    $technology->watingTerms != '-') {
                    $textTechnology.= "Умови харчування: " . $technology->watingTerms . ",\n";
                }

                if ($technology->features != NULL &&
                    $technology->features != '' &&
                    $technology->features != ' ' &&
                    $technology->features != '-') {
                    $textTechnology.= "Функции: " . $technology->features . ",\n";
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


                $text[] = ['technology' => $textTechnology, 'culture' => $nameCulture];
            }

            foreach ($text as $item) {
                $this->bot->sendText('Культури:' . $item['culture']);
                $this->bot->sendText('Застосування:' . $item['technology']);
            }
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
        $this->bot->sendText(strip_tags($productDescription));
    }

    public function price()
    {
        $this->bot->sendText('бази з цінами поки немає');
    }


}