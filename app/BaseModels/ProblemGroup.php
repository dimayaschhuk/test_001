<?php

namespace App\BaseModels;

use Illuminate\Database\Eloquent\Model;

class ProblemGroup extends Model
{
    protected $table = 'pd_VerminGroup';

    public $fillable = [
        'name',
    ];
}
