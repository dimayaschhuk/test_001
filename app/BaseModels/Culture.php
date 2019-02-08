<?php

namespace App\BaseModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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


    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'pd_CultureForCropProcessing', 'cultureId', 'cropProcessingId');
    }

    public function getProblemGroup()
    {
        $cropProcessingId = DB::table('pd_CultureForCropProcessing')
            ->where('cultureId', $this->id)
            ->pluck('cropProcessingId')
            ->toArray();

        $verminGroupId = DB::table('pd_VerminForCropProcessing')
            ->whereIn('cropProcessingId', $cropProcessingId)
            ->pluck('verminGroupId')
            ->toArray();

        $verminId = DB::table('pd_VerminForCropProcessing')
            ->whereIn('cropProcessingId', $cropProcessingId)
            ->pluck('verminId')
            ->toArray();

        $verminGroups = ProblemGroup::whereIn('id', $verminGroupId)->get();

        $problemsGroups = [];
        foreach ($verminGroups as $problemsGroup) {
            $problemsGroups[$problemsGroup->name] = [
                'problemName'    => Problem::whereIn('id', $verminId)
                    ->where('groupId', $problemsGroup->id)
                    ->pluck('name')
                    ->toArray(),
                'problemGroupId' => $problemsGroup->id,
            ];
        }

        return $problemsGroups;
    }

    public function getProblemGroupNames()
    {
        $problemGroupsNames = [];
        foreach ($this->getProblemGroup() as $key => $item) {
            $problemGroupsNames[] = $key;
        }

        return $problemGroupsNames;
    }

    public function getProblemNames($problemGroupId = NULL)
    {

        $problemNames = [];
        foreach ($this->getProblemGroup() as $key => $item) {
            if ($problemGroupId == $item['problemGroupId']) {
                $problemNames = $item['problemName'];
            }

        }

        return $problemNames;
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
