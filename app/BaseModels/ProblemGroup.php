<?php

namespace App\BaseModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProblemGroup extends Model
{
    protected $table = 'pd_VerminGroup';

    public $fillable = [
        'name',
    ];

    public function problems()
    {
        return $this->hasMany(Problem::class, 'groupId');
    }
}
