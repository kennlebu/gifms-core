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

class MobilePaymentTypeApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addMobilePaymentType
     *
     * Add a new mobile_payment_type.
     *
     *
     * @return Http response
     */
    public function addMobilePaymentType()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addMobilePaymentType');
        }
        $body = $input['body'];


        return response('How about implementing addMobilePaymentType as a POST method ?');
    }
    /**
     * Operation updateMobilePaymentType
     *
     * Update an existing mobile_payment_type.
     *
     *
     * @return Http response
     */
    public function updateMobilePaymentType()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateMobilePaymentType');
        }
        $body = $input['body'];


        return response('How about implementing updateMobilePaymentType as a PUT method ?');
    }
    /**
     * Operation deleteMobilePaymentType
     *
     * Deletes an mobile_payment_type.
     *
     * @param int $mobile_payment_type_id mobile_payment_type id to delete (required)
     *
     * @return Http response
     */
    public function deleteMobilePaymentType($mobile_payment_type_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteMobilePaymentType as a DELETE method ?');
    }
    /**
     * Operation getMobilePaymentTypeById
     *
     * Find mobile_payment_type by ID.
     *
     * @param int $mobile_payment_type_id ID of mobile_payment_type to return object (required)
     *
     * @return Http response
     */
    public function getMobilePaymentTypeById($mobile_payment_type_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getMobilePaymentTypeById as a GET method ?');
    }
    /**
     * Operation mobilePaymentTypesGet
     *
     * mobile_payment_types List.
     *
     *
     * @return Http response
     */
    public function mobilePaymentTypesGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $mobile_payment_type_id = $input['mobile_payment_type_id'];


        return response('How about implementing mobilePaymentTypesGet as a GET method ?');
    }
}
