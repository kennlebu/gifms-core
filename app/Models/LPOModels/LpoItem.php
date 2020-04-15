<?php

namespace App\Models\LPOModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;

class LpoItem extends BaseModel
{
    //
    use SoftDeletes;


	protected $appends = ['calculated_unit_price','calculated_vat','calculated_sub_total', 'calculated_total'];
	
    public function lpo()
    {
        return $this->belongsTo('App\Models\LPOModels\Lpo');
	}
	public function requisition_item()
    {
        return $this->belongsTo('App\Models\Requisitions\RequisitionItem', 'requisition_item_id');
	}
	
	public function getVatRateAttribute($value){
		return ($value ?? 16);
	}

    public function getCalculatedUnitPriceAttribute()
    {	
    	$up = (float) $this->unit_price;
    	$vat_charge = (int) $this->vat_charge;
    	$cup = $up;

    	if($vat_charge==1){
    		$cup = (100/(100 + $this->vat_rate))*$up;
    	}

        return $cup;
    }
    public function getCalculatedVatAttribute()
    {
    	$up = (float) $this->unit_price;
    	$vat_charge = (int) $this->vat_charge;
		$qty = (int) $this->qty;
		$days = 1;										// Set default no of days to 1 so that everything is multiplied
		if(!empty($this->no_of_days)){					// by 1 hence no difference. Otherwise, set it to the set no of 
			$days = $this->no_of_days;					// days in the DB
		}
    	$vat = 0 ;

    	if($vat_charge==0){
    		$vat = ($this->vat_rate/100)*$up*$qty*$days;
    	}
    	if($vat_charge==1){
    		$vat = ($this->vat_rate/(100 + $this->vat_rate))*$up*$qty*$days;
    	}

        return $vat;

    }
    public function getCalculatedTotalAttribute()
    {
    	$up = (float) $this->unit_price;
    	$vat_charge = (int) $this->vat_charge;
		$qty = (int) $this->qty;
		$days = 1;										// Set default no of days to 1 so that everything is multiplied
		if(!empty($this->no_of_days)){					// by 1 hence no difference. Otherwise, set it to the set no of 
			$days = $this->no_of_days;					// days in the DB
		}
    	$cup = $up;

    	if($vat_charge==1){
    		$cup = (100/(100 + $this->vat_rate))*$up;
    	}

		return (($cup*$qty*$days) + $this->calculated_vat);

    }
    public function getCalculatedSubTotalAttribute()
    {
		return ($this->calculated_total - $this->calculated_vat);
    }
}
