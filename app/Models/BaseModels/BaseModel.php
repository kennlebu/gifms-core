<?php

namespace App\Models\BaseModels;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BaseModel extends Model
{
    use LogsActivity;

    


	// public function newPivot(Eloquent $parent, array $attributes, $table, $exists){
	//     return new BaseModel($parent, $attributes, $table, $exists);
	// }

	

	









	protected function arr_to_dt_response($data,$draw,$total_records,$records_filtered){

		foreach ($data as $key => $value) {
			$data[$key]['DT_RowId'] = 'row_'.$value['id'];
			$data[$key]['DT_RowData'] = array('pkey'=>$value['id']);
		}

		return [
					'draw' 				=> 	$draw,
					'sEcho' 			=> 	$draw,
					'recordsTotal' 		=> 	$total_records,
					'recordsFiltered' 	=> 	$records_filtered,
					'data' 				=> 	$data
				];

	}

	









	protected function bind_presql($sql, $bindings){
		
		$needle = '?';

        foreach ($bindings as $replace){
            $pos = strpos($sql, $needle);
            if ($pos !== false) {
                $sql = substr_replace($sql, $replace, $pos, strlen($needle));
            }
        }
        return $sql;

	}

	









	protected function generateRandomString($length = 7) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}








	protected function generate_payable_payment($payable){

        $status = App\Models\PaymentModels\PaymentStatus::where('default_status','1')->first();
        $default_status = $status->id;


		$payment = new App\Models\PaymentModels\Payment;

		$payment->payable_type 				= 	$payable['payable_type'];
		$payment->payable_id 				= 	$payable['payable_id'];
		$payment->debit_bank_account_id		= 	$payable['debit_bank_account_id'];
		$payment->currency_id		 		= 	$payable['currency_id'];
		$payment->payment_desc		 		= 	$payable['payment_desc'];
		$payment->paid_to_name		 		= 	$payable['paid_to_name'];
		$payment->paid_to_mobile_phone_no	= 	$payable['paid_to_mobile_phone_no'];
		$payment->paid_to_bank_account_no	= 	$payable['paid_to_bank_account_no'];
		$payment->paid_to_bank_id		 	= 	$payable['paid_to_bank_id'];
		$payment->paid_to_bank_branch_id	= 	$payable['paid_to_bank_branch_id'];
		$payment->payment_mode_id		 	= 	$payable['payment_mode_id'];
		$payment->amount		 			= 	$payable['amount'];
		$payment->payment_batch_id		 	= 	$payable['payment_batch_id'];
		$payment->bank_charges		 		= 	$payable['bank_charges'];

		$payment->status_id		 			= 	$default_status;

		if($payment->save()) {

            $payment->ref                        = "CHAI/PYMT/#$payment->id/".date_format($payment->created_at,"Y/m/d");
            $payment->save();
            
            // return Response()->json(array('success' => 'Payable Added','payment' => $payment), 200);
        }
	}

	/**
     * Adds zeros at the beginning of string until the desired
     * length is reached.
     */
    public function pad($desired_length, $data){
        if(strlen($data)<$desired_length){
            return str_repeat('0', $desired_length-strlen($data)).$data;
        }
        else{
            return $data;
        }
    }
		

	/**
	 * Returns an array item matched by value
	 */
	public function getArrItem(array $arr, $key, $value){
		foreach($arr as $item){
			if($item->$key == $value){
				return $item;
			}
		}
	}


	/**
	 * Returns an array with matching values given a key
	 */
	public function pluck($ARR, $key, $value) {
		$RESULTS = [];
		foreach ($ARR as $item){
			if ($item[$key] == $value) {
				$RESULTS[] = $item;
			}
		}
		return $RESULTS;
	}


	/**
	 * Returns sum of array values by key
	 */
	public function sumArrayColumn($ARR, $key){
		$sum = 0;
		foreach ($ARR as $item){
			$sum += (float) $item[$key];
		}
		return $sum;
	}


	/**
	 * Returns unique values from an array or iterable object, 
	 * given the key
	 */
	public function uniqueArrayValues($ARR, $key){
		$uniques = array();
        foreach ($ARR as $item) {
            $uniques[] = $item[$key];
        }
        return array_unique($uniques);
	}

}
