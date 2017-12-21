<?php

namespace App\Models\GrantModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class Grant extends BaseModel
{
    //
    use SoftDeletes;

    protected $appends = ['amount_allocated'];

    public function status()
    {
        return $this->belongsTo('App\Models\GrantModels\GrantStatus','status_id');
    }
    public function currency()
    {
        return $this->belongsTo('App\Models\LookupModels\Currency','currency_id');
    }
    public function donor()
    {
        return $this->belongsTo('App\Models\GrantModels\Donor','donor_id');
    }
    // public function projects()
    // {
    //     return $this->hasMany('App\Models\ProjectsModels\Project');
    // } 
    public function grant_allocations()
    {
        return $this->hasMany('App\Models\GrantModels\GrantAllocation');
    }
    public function account_restrictions()
    {
        return $this->hasMany('App\Models\GrantModels\GrantAccountRestriction');
    }
    
    public function getAmountAllocatedAttribute(){

        $grant_allocations     =   $this->grant_allocations;
        $totals    =   0;

        foreach ($grant_allocations as $key => $value) {
            $totals    +=  (float) $value->amount_allocated;
        }

        return $totals;

    }
}
