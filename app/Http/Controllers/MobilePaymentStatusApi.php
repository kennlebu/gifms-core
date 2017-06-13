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
use App\Models\MobilePaymentModels\MobilePaymentStatus;

class MobilePaymentStatusApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addMobilePaymentStatus
     *
     * Add a new mobile_payment_status.
     *
     *
     * @return Http response
     */
    public function addMobilePaymentStatus()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addMobilePaymentStatus');
        }
        $body = $input['body'];


        return response('How about implementing addMobilePaymentStatus as a POST method ?');
    }
    /**
     * Operation updateMobilePaymentStatus
     *
     * Update an existing mobile_payment_status.
     *
     *
     * @return Http response
     */
    public function updateMobilePaymentStatus()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateMobilePaymentStatus');
        }
        $body = $input['body'];


        return response('How about implementing updateMobilePaymentStatus as a PUT method ?');
    }
    /**
     * Operation deleteMobilePaymentStatus
     *
     * Deletes an mobile_payment_status.
     *
     * @param int $mobile_payment_status_id mobile_payment_status id to delete (required)
     *
     * @return Http response
     */
    public function deleteMobilePaymentStatus($mobile_payment_status_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteMobilePaymentStatus as a DELETE method ?');
    }
    /**
     * Operation getMobilePaymentStatusById
     *
     * Find mobile_payment_status by ID.
     *
     * @param int $mobile_payment_status_id ID of mobile_payment_status to return object (required)
     *
     * @return Http response
     */
    public function getMobilePaymentStatusById($mobile_payment_status_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getMobilePaymentStatusById as a GET method ?');
    }




































    /**
     * Operation mobilePaymentStatusesGet
     *
     * mobile_payment_statuses List.
     *
     *
     * @return Http response
     */
    public function mobilePaymentStatusesGet()
    { 

        $input = Request::all();

        //path params validation
        $response;

        //path params validation


        //if status is set

        if(array_key_exists('staff_id', $input)){

     

        }else{

             $response = MobilePaymentStatus::where("deleted_at",null)
                ->orderBy('mobile_payment_status', 'desc')
                ->get();
        }


           


            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}
