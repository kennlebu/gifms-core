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
use Illuminate\Support\Facades\DB;
use Anchu\Ftp\Facades\Ftp;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\PaymentModels\PaymentVoucher;

class PaymentVoucherApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

























    

    /**
     * Operation addPaymentVoucher
     *
     * Add a new payment_voucher.
     *
     *
     * @return Http response
     */
    public function addPaymentVoucher()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addPaymentVoucher');
        }
        $body = $input['body'];


        return response('How about implementing addPaymentVoucher as a POST method ?');
    }

























    
    /**
     * Operation updatePaymentVoucher
     *
     * Update an existing payment_voucher.
     *
     *
     * @return Http response
     */
    public function updatePaymentVoucher()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updatePaymentVoucher');
        }
        $body = $input['body'];


        return response('How about implementing updatePaymentVoucher as a PUT method ?');
    }

























    
    /**
     * Operation getPaymentVoucherById
     *
     * Find payment_voucher by ID.
     *
     * @param int $payment_voucher_id ID of payment_voucher to return object (required)
     *
     * @return Http response
     */
    public function getPaymentVoucherById($payment_voucher_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getPaymentVoucherById as a GET method ?');
    }

























    
    /**
     * Operation deletePaymentVoucher
     *
     * Deletes an payment_voucher.
     *
     * @param int $payment_voucher_id payment_voucher id to delete (required)
     *
     * @return Http response
     */
    public function deletePaymentVoucher($payment_voucher_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deletePaymentVoucher as a DELETE method ?');
    }

























    
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

        try {

            $payment_voucher        = PaymentVoucher::findOrFail($payment_voucher_id);

            //load signatures

            foreach ($payment_voucher->vouchable->approvals as $key => $approval) {
                try {                    
                    $signature = $approval->approver->signature_url;
                } catch (Exception $e) {
                    
                }
            }

            $data           = array(
                'payment_voucher'   => $payment_voucher
            );

            $pdf            = PDF::loadView('pdf/payment_voucher', $data);

            $file_contents  = $pdf->stream();

            Storage::put('payment_voucher/'.$payment_voucher_id.'.voucher.temp', $file_contents);

            $url            = storage_path("app/payment_voucher/".$payment_voucher_id.'.voucher.temp');

            $file           = File::get($url);

            $response       = Response::make($file, 200);

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
