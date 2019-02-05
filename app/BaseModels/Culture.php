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

    public function getProblemGroupNames()
    {
        $problemGroupsNames = [];
        foreach ($this->technologies as $technology) {
            foreach ($technology->problem_groups as $problem_group) {
                if (!in_array($problem_group->name, $problemGroupsNames)) {
                    $problemGroupsNames[] = $problem_group->name;
                }
            }
        }

        return $problemGroupsNames;
    }

    public function getProblemNames($problemGroupId = NULL)
    {

        if ($problemGroupId != NULL) {
            $problemNames = ProblemGroup::find($problemGroupId)->problems->pluck('name')->toArray();
            $problemNames = array_intersect($problemNames, $this->getProblemNames());
            $ProblemNames = [];
            foreach ($problemNames as $item) {
                if (!in_array($item, $ProblemNames)) {
                    $problemGroupsNames[] = $item;
                }
            }

            return $ProblemNames;
        }

        $problemNames = [];
        $ProblemNames = [];
        foreach ($this->technologies as $technology) {
            $problemNames = array_merge($problemNames, $technology->problems->pluck('name')->toArray());
        }

        foreach ($problemNames as $item) {
            if (!in_array($item, $ProblemNames)) {
                $ProblemNames[] = $item;
            }
        }

        return $ProblemNames;
    }


    public function getProductsNames($problemId)
    {
        try {
            $productAllNames = [];
            foreach ($this->technologies as $technology) {
                $productAllNames[] = $technology->product->name;
            }
            $productNames = [];
            $problemTechnologies = Problem::find($problemId)->technologies;
            foreach ($problemTechnologies as $technology) {
                $productNames[] = $technology->product->name;
            }

            return array_intersect($productAllNames, $productNames);
        } catch (\ErrorException $errorException) {
            return [];
        }

    }


    public function getLIKEProblemNames($text)
    {
        $problemNames = Problem::where('name', 'LIKE', "%{$text}%")->pluck('name')->toArray();

        return array_intersect($problemNames, $this->getProblemNames());
    }


    public function checkProduct($nameProduct, $problemId)
    {
        return in_array($nameProduct, $this->getProductsNames($problemId));
    }

    public function checkProblem($nameProblem = '')
    {
        return in_array($nameProblem, $this->getProblemNames());
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
