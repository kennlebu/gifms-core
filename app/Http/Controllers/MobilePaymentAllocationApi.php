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

class MobilePaymentAllocationApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addMobilePaymentAllocation
     *
     * Add a new mobile_payment_allocation.
     *
     *
     * @return Http response
     */
    public function addMobilePaymentAllocation()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addMobilePaymentAllocation');
        }
        $body = $input['body'];


        return response('How about implementing addMobilePaymentAllocation as a POST method ?');
    }
    /**
     * Operation updateMobilePaymentAllocation
     *
     * Update an existing mobile_payment_allocation.
     *
     *
     * @return Http response
     */
    public function updateMobilePaymentAllocation()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateMobilePaymentAllocation');
        }
        $body = $input['body'];


        return response('How about implementing updateMobilePaymentAllocation as a PUT method ?');
    }
    /**
     * Operation deleteMobilePaymentAllocation
     *
     * Deletes an mobile_payment_allocation.
     *
     * @param int $mobile_payment_allocation_id mobile_payment_allocation id to delete (required)
     *
     * @return Http response
     */
    public function deleteMobilePaymentAllocation($mobile_payment_allocation_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteMobilePaymentAllocation as a DELETE method ?');
    }
    /**
     * Operation getMobilePaymentAllocationById
     *
     * Find mobile_payment_allocation by ID.
     *
     * @param int $mobile_payment_allocation_id ID of mobile_payment_allocation to return object (required)
     *
     * @return Http response
     */
    public function getMobilePaymentAllocationById($mobile_payment_allocation_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getMobilePaymentAllocationById as a GET method ?');
    }
    /**
     * Operation mobilePaymentAllocationsGet
     *
     * mobile_payment_allocations List.
     *
     *
     * @return Http response
     */
    public function mobilePaymentAllocationsGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $mobile_payment_allocation_id = $input['mobile_payment_allocation_id'];


        return response('How about implementing mobilePaymentAllocationsGet as a GET method ?');
    }
}
