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

		return array(
					'draw' 				=> 	$draw,
					'sEcho' 			=> 	$draw,
					'recordsTotal' 		=> 	$total_records,
					'recordsFiltered' 	=> 	$records_filtered,
					'data' 				=> 	$data
			);

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

	protected function generate_voucher_no($payable_id, $payable_type, $creation_date){
		// $prefix = '';
		// // Now update the invoices, claims and advances
		// if($payable_type == 'invoices'){
		// 	$prefix = '-INV';
		// }
		// elseif($payable_type == 'advances'){
		// 	$prefix = '-ADV';
		// }
		// elseif($payable_type == 'claims'){
		// 	$prefix = '-CLM';
		// }
		
		$last_voucher_no = '';

		$new_voucher = new App\Models\PaymentModels\VoucherNumber;
		$new_voucher->payable_type = $payable_type;
		$new_voucher->payable_id = $payable_id;

		$previous_voucher = App\Models\PaymentModels\VoucherNumber::latest()->first();
		if(empty($previous_voucher->voucher_number)){
			$last_voucher_no = 'KE180000-NEW';
		}
		else {
			$last_voucher_no = $previous_voucher->voucher_number;
		}

		$year = date_format($creation_date,'Y');
		$year = substr($year, -2);

		if($year != substr($last_voucher_no, 2, 2)){
			// start new series
			$last_voucher_no = 'KE180000-NEW';
		}
		
		$voucher_no = ((int) substr($last_voucher_no, 4, 4)) + 1;
		$voucher_no = 'KE'.$year.$this->pad_zeros(4, $voucher_no);
		
		$new_voucher->voucher_number = $voucher_no;

		if($new_voucher->save()){
			return array(
				'id' => $new_voucher->id,
				'voucher' => $new_voucher->voucher_number
			);
		}
		

	}


}
