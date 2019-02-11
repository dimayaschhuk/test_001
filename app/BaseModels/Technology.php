<?php

namespace App\BaseModels;

use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    protected $table = 'pd_TechnologyCropProcessing';

//    public $fillable = [
//        'productId',
//    ];

    protected $appends = [
        'problem_groups',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productId');
    }

    public function problems()
    {
        return $this->belongsToMany(Problem::class, 'pd_VerminForCropProcessing', 'cropProcessingId', 'verminId');
    }

    public function verminGroups()
    {
        return $this->belongsToMany(ProblemGroup::class, 'pd_VerminForCropProcessing', 'cropProcessingId',
            'verminGroupId');
    }


    public function getProblemGroupsAttribute()
    {
        $problemGroupsIds=[];
        $problemGroups=collect();
        foreach ($this->verminGroups as $problemGroup){
            if(!in_array($problemGroup->id,$problemGroupsIds)){
                $problemGroupsIds[]=$problemGroup->id;
                $problemGroups->push($problemGroup);
            }
        }

        return $problemGroups;
    }
}
