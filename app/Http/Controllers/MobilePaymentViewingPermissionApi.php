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

class MobilePaymentViewingPermissionApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addMobilePaymentViewingPermission
     *
     * Add a new mobile_payment_viewing_permission.
     *
     *
     * @return Http response
     */
    public function addMobilePaymentViewingPermission()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addMobilePaymentViewingPermission');
        }
        $body = $input['body'];


        return response('How about implementing addMobilePaymentViewingPermission as a POST method ?');
    }
    /**
     * Operation updateMobilePaymentViewingPermission
     *
     * Update an existing mobile_payment_viewing_permission.
     *
     *
     * @return Http response
     */
    public function updateMobilePaymentViewingPermission()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateMobilePaymentViewingPermission');
        }
        $body = $input['body'];


        return response('How about implementing updateMobilePaymentViewingPermission as a PUT method ?');
    }
    /**
     * Operation deleteMobilePaymentViewingPermission
     *
     * Deletes an mobile_payment_viewing_permission.
     *
     * @param int $mobile_payment_viewing_permission_id mobile_payment_viewing_permission id to delete (required)
     *
     * @return Http response
     */
    public function deleteMobilePaymentViewingPermission($mobile_payment_viewing_permission_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteMobilePaymentViewingPermission as a DELETE method ?');
    }
    /**
     * Operation getMobilePaymentViewingPermissionById
     *
     * Find mobile_payment_viewing_permission by ID.
     *
     * @param int $mobile_payment_viewing_permission_id ID of mobile_payment_viewing_permission to return object (required)
     *
     * @return Http response
     */
    public function getMobilePaymentViewingPermissionById($mobile_payment_viewing_permission_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getMobilePaymentViewingPermissionById as a GET method ?');
    }
    /**
     * Operation mobilePaymentViewingPermissionsGet
     *
     * mobile_payment_viewing_permissions List.
     *
     *
     * @return Http response
     */
    public function mobilePaymentViewingPermissionsGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $mobile_payment_viewing_permission_id = $input['mobile_payment_viewing_permission_id'];


        return response('How about implementing mobilePaymentViewingPermissionsGet as a GET method ?');
    }
}
