<?php

namespace App\BaseModels;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    protected $table = 'pd_Vermin';

//    public $fillable = [
//        'name',
//        'guid',
//        'groupId',
//        'photo',
//        'ord',
//    ];

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'pd_VerminForCropProcessing','verminId','cropProcessingId');
    }
}
