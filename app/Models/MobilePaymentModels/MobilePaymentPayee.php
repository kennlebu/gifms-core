<?php

namespace App\Models\MobilePaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use App\Models\LookupModels\Region;
use App\Models\LookupModels\County;
use DB;

class MobilePaymentPayee extends BaseModel
{
    //
    use SoftDeletes;





    protected $appends = ['calculated_withdrawal_charges','calculated_total'];

    protected $hidden = ['county','deleted_at','designation','email','migration_id','migration_mobile_payment_id','paid','payment_reference',
                        'region','sub_county'];


    public function getCalculatedWithdrawalChargesAttribute(){

    	$amount = (double) $this->attributes['amount'];

        $withdrawal_charges = 0 ;


        $tariff_res = DB::table('mobile_payment_tariffs')
                     ->select(DB::raw('tariff'))
                     ->where('min_limit', '<=', $amount)
                     ->where('max_limit', '>=', $amount)
                     ->get();

        // print_r($tariff_res);
        // die;

        foreach ($tariff_res as $key => $value) {
            
            $withdrawal_charges = (double)  $value['tariff'] ;

        }

        return $withdrawal_charges;
        
    }







    public function getCalculatedTotalAttribute(){

    	$amount = (double) $this->attributes['amount'];

        return  $amount + (double)	$this->calculated_withdrawal_charges;
    }

}
