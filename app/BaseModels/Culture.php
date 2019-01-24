<?php

namespace App\BaseModels;

use Illuminate\Database\Eloquent\Model;

class Culture extends Model
{
    protected $table = 'pd_Culture';

    public $fillable = [
        'name',
        'guid',
        'picture',
        'description',
        'groupId',
        'emptySchemaImage',
        'type',
        'ord'
    ];
}
