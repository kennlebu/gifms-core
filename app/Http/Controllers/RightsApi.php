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

class RightsApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }




























    

    /**
     * Operation addRight
     *
     * Add a new right.
     *
     *
     * @return Http response
     */
    public function addRight()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addRight');
        }
        $body = $input['body'];


        return response('How about implementing addRight as a POST method ?');
    }




























    
    /**
     * Operation updateRight
     *
     * Update an existing right.
     *
     *
     * @return Http response
     */
    public function updateRight()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateRight');
        }
        $body = $input['body'];


        return response('How about implementing updateRight as a PUT method ?');
    }




























    
    /**
     * Operation deleteRight
     *
     * Deletes an right.
     *
     * @param int $right_id right id to delete (required)
     *
     * @return Http response
     */
    public function deleteRight($right_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteRight as a DELETE method ?');
    }




























    
    /**
     * Operation getRightById
     *
     * Find right by ID.
     *
     * @param int $right_id ID of right to return object (required)
     *
     * @return Http response
     */
    public function getRightById($right_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getRightById as a GET method ?');
    }




























    
    /**
     * Operation rightsGet
     *
     * rights List.
     *
     *
     * @return Http response
     */
    public function rightsGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $right_id = $input['right_id'];


        return response('How about implementing rightsGet as a GET method ?');
    }
}
