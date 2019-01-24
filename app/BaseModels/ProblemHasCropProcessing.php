<?php

namespace App\BaseModels;

use Illuminate\Database\Eloquent\Model;

class ProblemHasCropProcessing extends Model
{
    protected $table = 'pd_VerminForCropProcessing';

    public $fillable = [
        'verminId',
        'cropProcessingId',
        'verminGroupId',
    ];
}
