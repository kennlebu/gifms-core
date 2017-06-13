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

class MobilePaymentPayeeApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addMobilePaymentPayee
     *
     * Add a new mobile_payment_payee.
     *
     *
     * @return Http response
     */
    public function addMobilePaymentPayee()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addMobilePaymentPayee');
        }
        $body = $input['body'];


        return response('How about implementing addMobilePaymentPayee as a POST method ?');
    }
    /**
     * Operation updateMobilePaymentPayee
     *
     * Update an existing mobile_payment_payee.
     *
     *
     * @return Http response
     */
    public function updateMobilePaymentPayee()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateMobilePaymentPayee');
        }
        $body = $input['body'];


        return response('How about implementing updateMobilePaymentPayee as a PUT method ?');
    }
    /**
     * Operation deleteMobilePaymentPayee
     *
     * Deletes an mobile_payment_payee.
     *
     * @param int $mobile_payment_payee_id mobile_payment_payee id to delete (required)
     *
     * @return Http response
     */
    public function deleteMobilePaymentPayee($mobile_payment_payee_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteMobilePaymentPayee as a DELETE method ?');
    }
    /**
     * Operation getMobilePaymentPayeeById
     *
     * Find mobile_payment_payee by ID.
     *
     * @param int $mobile_payment_payee_id ID of mobile_payment_payee to return object (required)
     *
     * @return Http response
     */
    public function getMobilePaymentPayeeById($mobile_payment_payee_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getMobilePaymentPayeeById as a GET method ?');
    }
    /**
     * Operation mobilePaymentPayeesGet
     *
     * mobile_payment_payees List.
     *
     *
     * @return Http response
     */
    public function mobilePaymentPayeesGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $mobile_payment_payee_id = $input['mobile_payment_payee_id'];


        return response('How about implementing mobilePaymentPayeesGet as a GET method ?');
    }
}
