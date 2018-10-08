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


    public function getCalculatedUnitPriceAttribute()
    {	
    	$up 			=	(float)		$this->attributes['unit_price'];
    	$vat_charge 	=	(int)		$this->attributes['vat_charge'];
    	$cup 			=	$up;

    	if($vat_charge==1){
    		$cup 	= 	(100/116)*$up;
    	}

        return $cup;

    }
    public function getCalculatedVatAttribute()
    {
    	$up 			=	(float)		$this->attributes['unit_price'];
    	$vat_charge 	=	(int)		$this->attributes['vat_charge'];
		$qty 			=	(int)		$this->attributes['qty'];
		$days = 1;										// Set default no of days to 1 so that everything is multiplied
		if(!empty($this->attributes['no_of_days'])){	// by 1 hence no difference. Otherwise, set it to the set no of 
			$days = $this->attributes['no_of_days'];	// days in the DB
		}
    	$vat = 0 ;
    	$cup 			=	$up;

    	if($vat_charge==0){
    		$vat 	= 	(16/100)*$up*$qty*$days;
    	}
    	if($vat_charge==1){
    		$vat 	= 	(16/116)*$up*$qty*$days;
    		$cup 	= 	(100/116)*$up;
    	}

        return $vat;

    }
    public function getCalculatedSubTotalAttribute()
    {
    	$up 			=	(float)		$this->attributes['unit_price'];
    	$vat_charge 	=	(int)		$this->attributes['vat_charge'];
		$qty 			=	(int)		$this->attributes['qty'];
		$days = 1;										// Set default no of days to 1 so that everything is multiplied
		if(!empty($this->attributes['no_of_days'])){	// by 1 hence no difference. Otherwise, set it to the set no of 
			$days = $this->attributes['no_of_days'];	// days in the DB
		}
    	$vat = 0;
    	$cup 			=	$up;
    	$st;

    	if($vat_charge==0){
    		$vat 	= 	(16/100)*$up*$qty*$days;
    	}
    	if($vat_charge==1){
    		$vat 	= 	(16/116)*$up*$qty*$days;
    		$cup 	= 	(100/116)*$up;
    	}

    	$st = ($cup*$qty*$days) ;

        return $st;

    }
    public function getCalculatedTotalAttribute()
    {
    	$up 			=	(float)		$this->attributes['unit_price'];
    	$vat_charge 	=	(int)		$this->attributes['vat_charge'];
		$qty 			=	(int)		$this->attributes['qty'];
		$days = 1;										// Set default no of days to 1 so that everything is multiplied
		if(!empty($this->attributes['no_of_days'])){	// by 1 hence no difference. Otherwise, set it to the set no of 
			$days = $this->attributes['no_of_days'];	// days in the DB
		}
    	$vat = 0;
    	$cup 			=	$up;
    	$st;

    	if($vat_charge==0){
    		$vat 	= 	(16/100)*$up*$qty*$days;
    	}
    	if($vat_charge==1){
    		$vat 	= 	(16/116)*$up*$qty*$days;
    		$cup 	= 	(100/116)*$up;
    	}

    	$st = ($cup*$qty*$days) + $vat;

        return $st;

    }
}
