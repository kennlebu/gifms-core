<?php

namespace App\Models\MobilePaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\LookupModels\Region;
use App\Models\LookupModels\County;

class MobilePaymentPayee extends BaseModel
{
    //
    use SoftDeletes;


    protected $appends = [
                            'region',
                            'county'
                        ];



    public function getRegionAttribute()
    {
        

	        return Region::find($this->attributes['region_id']);
	    

    }
    public function getCountyAttribute()
    {
        

	        return County::find($this->attributes['county_id']);
	    

    }
}
