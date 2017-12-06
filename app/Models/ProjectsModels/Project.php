<?php

namespace App\Models\ProjectsModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Project extends BaseModel
{
    //
    use SoftDeletes;


    protected $appends = ['grant_amount_allocated','total_expenditure'];

    public function program()
    {
        return $this->belongsTo('App\Models\ProgramModels\Program','program_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\ProjectsModels\ProjectStatus','status_id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\LookupModels\Country','country_id');
    }
    public function staffs()
    {
        return $this->belongsToMany('App\Models\StaffModels\Staff', 'project_teams', 'project_id', 'staff_id');
    }
    public function budget()
    {
        return $this->belongsTo('App\Models\FinanceModels\Budget', 'budget_id');
    }
    public function grant_allocations()
    {
        return $this->hasMany('App\Models\GrantModels\GrantAllocation','project_id');
    }
    public function allocations()
    {
        return $this->hasMany('App\Models\AllocationModels\Allocation','project_id');
    }
    // public function getAmountAllocatedAttribute(){

    //     $grant_allocations     =   $this->grant_allocations;
    //     $totals    =   0;

    //     foreach ($grant_allocations as $key => $value) {
    //         $totals    +=  (float) $value->amount_allocated;
    //     }

    //     return $totals;

    // }
    public function getGrantAmountAllocatedAttribute(){

        $grant_allocations     =   $this->grant_allocations;
        $totals    =   0;

        foreach ($grant_allocations as $key => $value) {
            $totals    +=  (float) $value->amount_allocated;
        }

        return $totals;

    }
    public function getTotalExpenditureAttribute(){

        $allocations     =   $this->allocations;
        $totals    =   0;

        foreach ($allocations as $key => $value) {
            $totals    +=  (float) $value->amount_allocated;
        }

        return $totals;

    }
}
