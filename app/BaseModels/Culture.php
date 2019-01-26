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
        'ord',
    ];

//    protected $appends = [
//        'problem_id',
//    ];

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'pd_CultureForCropProcessing', 'cultureId', 'cropProcessingId');
    }

    public function getProductsNames($problemId)
    {
        $productAllNames = [];
        foreach ($this->technologies as $technology) {
            $productAllNames[] = $technology->product->name;
        }
        $productNames = [];
        $problemtTechnologies = Problem::find($problemId)->technologies;
        foreach ($problemtTechnologies as $technology) {
            $productNames[] = $technology->product->name;
        }

        return array_intersect($productAllNames, $productNames);
    }

    public function getProblemGroupNames()
    {
        $problemGroupsNames = [];
        foreach ($this->technologies as $technology) {
            $problemGroupsNames = array_merge($problemGroupsNames,
                $technology->problem_groups->pluck('name')->toArray());
        }

        return $problemGroupsNames;
    }

    public function getProblemNames($problemGroupId = NULL)
    {

        if ($problemGroupId != NULL) {
            $problemNames = ProblemGroup::find($problemGroupId)->problems->pluck('name')->toArray();

            return array_intersect($problemNames, $this->getProblemNames());
        }

        $problemNames = [];
        foreach ($this->technologies as $technology) {
            $problemNames = array_merge($problemNames, $technology->problems->pluck('name')->toArray());
        }

        return $problemNames;
    }

    public function getLIKEProblemNames($text)
    {
        $problemNames = Problem::where('name', 'LIKE', "%{$text}%")->pluck('name')->toArray();

        return array_intersect($problemNames, $this->getProblemNames());
    }


    public function checkProblem($nameProblem)
    {
        if (in_array($nameProblem, $this->getProblemNames())) {
            return TRUE;
        }
        return FALSE;
    }

    public function checkProblemGroup($nameProblemGroup)
    {
        return in_array($nameProblemGroup, $this->getProblemGroupNames());
    }

    public function checkLIKEProblem($nameProblem)
    {
        $problemNames = Problem::where('name', 'LIKE', "%{$nameProblem}%")->pluck('name')->toArray();
        if (count(array_intersect($problemNames, $this->getProblemNames())) > 0) {
            return TRUE;
        }

        return FALSE;
    }

    public function checkLIKEProblemGroup($nameProblemGroup)
    {
        $problemGroupNames = ProblemGroup::where('name', 'LIKE', "%{$nameProblemGroup}%")->pluck('name')->toArray();
        if (count(array_intersect($problemGroupNames, $this->getProblemGroupNames())) > 0) {
            return TRUE;
        }

        return FALSE;
    }


}
