<?php

namespace App\BaseModels;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
    protected $table = 'pd_ProductGroup';

    public function getBrandNames()
    {
        $brandIds = Product::where('groupId', $this->id)
            ->pluck('brandId')
            ->toArray();

        return Brand::whereIn('id', $brandIds)->pluck('name')->toArray();
    }
}
