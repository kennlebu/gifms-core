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

class RolesApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }



























    

    /**
     * Operation addRole
     *
     * Add a new role.
     *
     *
     * @return Http response
     */
    public function addRole()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addRole');
        }
        $body = $input['body'];


        return response('How about implementing addRole as a POST method ?');
    }



























    
    /**
     * Operation updateRole
     *
     * Update an existing role.
     *
     *
     * @return Http response
     */
    public function updateRole()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateRole');
        }
        $body = $input['body'];


        return response('How about implementing updateRole as a PUT method ?');
    }



























    
    /**
     * Operation deleteRole
     *
     * Deletes an role.
     *
     * @param int $role_id role id to delete (required)
     *
     * @return Http response
     */
    public function deleteRole($role_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteRole as a DELETE method ?');
    }



























    
    /**
     * Operation getRoleById
     *
     * Find role by ID.
     *
     * @param int $role_id ID of role to return object (required)
     *
     * @return Http response
     */
    public function getRoleById($role_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getRoleById as a GET method ?');
    }



























    
    /**
     * Operation rolesGet
     *
     * roles List.
     *
     *
     * @return Http response
     */
    public function rolesGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $role_id = $input['role_id'];


        return response('How about implementing rolesGet as a GET method ?');
    }
}
