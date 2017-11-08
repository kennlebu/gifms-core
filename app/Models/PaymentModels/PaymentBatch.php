<?php

namespace App\Models\PaymentModels;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModels\BaseModel;
use DB;

class PaymentBatch extends BaseModel
{
    //
    use SoftDeletes;
    protected $appends = ['payment_modes'];

    public function processed_by()
    {
        return $this->belongsTo('App\Models\StaffModels\Staff','processed_by_id');
    }



    public function getPaymentModesAttribute(){


    	$id = (int) $this->attributes['id'];


        $tariff_res = DB::table('payments')
                     ->select(DB::raw(' payment_modes.id, payment_modes.abrv,payments.currency_id, currencies.currency_sign, currencies.currency_name, currencies.display_color, currencies.default_currency '))
                     ->leftJoin('payment_modes', 'payments.payment_mode_id', '=', 'payment_modes.id')
                     ->leftJoin('currencies', 'payments.currency_id', '=', 'currencies.id')
                     ->where('payments.payment_batch_id', $id)
                     ->whereNotNull('payments.currency_id')
		             ->groupBy('payment_modes.abrv','payments.currency_id')
		             ->orderBy('payment_modes.abrv', 'ASC')
                     ->get();


        return $tariff_res;


    }

}
