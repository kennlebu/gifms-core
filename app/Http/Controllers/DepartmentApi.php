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

class DepartmentApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addDepartment
     *
     * Add a new department.
     *
     *
     * @return Http response
     */
    public function addDepartment()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addDepartment');
        }
        $body = $input['body'];


        return response('How about implementing addDepartment as a POST method ?');
    }
    /**
     * Operation updateDepartment
     *
     * Update an existing department.
     *
     *
     * @return Http response
     */
    public function updateDepartment()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateDepartment');
        }
        $body = $input['body'];


        return response('How about implementing updateDepartment as a PUT method ?');
    }
    /**
     * Operation deleteDepartment
     *
     * Deletes an department.
     *
     * @param int $department_id department id to delete (required)
     *
     * @return Http response
     */
    public function deleteDepartment($department_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteDepartment as a DELETE method ?');
    }
    /**
     * Operation getDepartmentById
     *
     * Find department by ID.
     *
     * @param int $department_id ID of department to return object (required)
     *
     * @return Http response
     */
    public function getDepartmentById($department_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getDepartmentById as a GET method ?');
    }
    /**
     * Operation departmentsGet
     *
     * departments List.
     *
     *
     * @return Http response
     */
    public function departmentsGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $department_id = $input['department_id'];


        return response('How about implementing departmentsGet as a GET method ?');
    }
}
