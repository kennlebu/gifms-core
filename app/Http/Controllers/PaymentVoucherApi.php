<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use PDF;
use Illuminate\Support\Facades\Response;
use App\Models\InvoicesModels\Invoice;
use App\Models\PaymentModels\VoucherNumber;

class PaymentVoucherApi extends Controller
{ 
    /**
     * Operation getDocumentById
     *
     * get payment_voucher document by ID.
     *
     * @param int $payment_voucher_id ID of payment_voucher to return object (required)
     *
     * @return Http response
     */
    public function getDocumentById($payment_voucher_id)
    {       

        try{
            $invoice        = Invoice::findOrFail($payment_voucher_id);
            $voucher_date = '-';
            $vendor = '-';
            $voucher_no = '-';
            if(empty($invoice->voucher_no)){
                if(empty($invoice->voucher_no)) $voucher_no = '-';
                else{
                    $voucher_no = 'CHAI'.$this->pad_zeros(5, $invoice->voucher_no);
                }
            }
            else{
                $voucher_no = VoucherNumber::first($invoice->voucher_no);
                $voucher_no = $voucher_no->voucher_number;
            }

            if(empty($invoice->payment_date)){
                $payment = Payment::where('payable_id', $invoice->id)->where('payable_type', 'invoices')->first();
                if(!empty($payment->payment_batch_id) && $payment->payment_batch_id > 0){
                    $batch = PaymentBatch::find($payment->payment_batch_id);
                    $voucher_date = $batch->created_at;
                }
            }
            else{
                $voucher_date = $invoice->payment_date;
            }

            $vendor = $invoice->supplier->supplier_name;

            $data = array(
                    'payable'   => $invoice,
                    'voucher_date' => $voucher_date,
                    'vendor'=>$vendor,
                    'voucher_no'=>$voucher_no,
                    'payable_type'=>'Invoice'
                    );

            $pdf            = PDF::loadView('pdf/payment_voucher', $data);
            $file_contents  = $pdf->stream();
            $response       = Response::make($file_contents, 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;
        }catch (Exception $e ){            
            $response       = Response::make("", 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;  
        }
    }

























    
    /**
     * Operation paymentVouchersGet
     *
     * payment_vouchers List.
     *
     *
     * @return Http response
     */
    public function paymentVouchersGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $payment_voucher_id = $input['payment_voucher_id'];


        return response('How about implementing paymentVouchersGet as a GET method ?');
    }
}
