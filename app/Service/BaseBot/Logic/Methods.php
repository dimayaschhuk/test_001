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
use App\Service\BaseBot\Logic\Flow\ProtectCulture;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait Methods
{

    use ProtectCulture;



    public function afterSelectedProduct()
    {
        $this->bot->sendText('afterSelectedProduct');
    }
}