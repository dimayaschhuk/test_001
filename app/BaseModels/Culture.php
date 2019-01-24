<?php

namespace App\BaseModels;

use Illuminate\Database\Eloquent\Model;

class Culture extends Model
{
    protected $table = 'admin_role_translations';

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
