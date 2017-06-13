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
    		$cup 	= 	(84/100)*$up;
    	}

        return $cup;

    }
    public function getCalculatedVatAttribute()
    {
    	$up 			=	(float)		$this->attributes['unit_price'];
    	$vat_charge 	=	(int)		$this->attributes['vat_charge'];
    	$qty 			=	(int)		$this->attributes['qty'];
    	$vat = "-" ;
    	$cup 			=	$up;

    	if($vat_charge==0){
    		$vat 	= 	(16/100)*$up*$qty;
    	}
    	if($vat_charge==1){
    		$vat 	= 	(16/100)*$up*$qty;
    		$cup 	= 	(84/100)*$up;
    	}

        return $vat;

    }
    public function getCalculatedSubTotalAttribute()
    {
    	$up 			=	(float)		$this->attributes['unit_price'];
    	$vat_charge 	=	(int)		$this->attributes['vat_charge'];
    	$qty 			=	(int)		$this->attributes['qty'];
    	$vat = 0;
    	$cup 			=	$up;
    	$st;

    	if($vat_charge==0){
    		$vat 	= 	(16/100)*$up*$qty;
    	}
    	if($vat_charge==1){
    		$vat 	= 	(16/100)*$up*$qty;
    		$cup 	= 	(84/100)*$up;
    	}

    	$st = ($cup*$qty) ;

        return $st;

    }
    public function getCalculatedTotalAttribute()
    {
    	$up 			=	(float)		$this->attributes['unit_price'];
    	$vat_charge 	=	(int)		$this->attributes['vat_charge'];
    	$qty 			=	(int)		$this->attributes['qty'];
    	$vat = 0;
    	$cup 			=	$up;
    	$st;

    	if($vat_charge==0){
    		$vat 	= 	(16/100)*$up*$qty;
    	}
    	if($vat_charge==1){
    		$vat 	= 	(16/100)*$up*$qty;
    		$cup 	= 	(84/100)*$up;
    	}

    	$st = ($cup*$qty) + $vat;

        return $st;

    }
}
