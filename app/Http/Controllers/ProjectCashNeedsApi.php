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

class ProjectCashNeedsApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addProjectCashNeeds
     *
     * Add a new project_cash_needs.
     *
     *
     * @return Http response
     */
    public function addProjectCashNeeds()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addProjectCashNeeds');
        }
        $body = $input['body'];


        return response('How about implementing addProjectCashNeeds as a POST method ?');
    }
    /**
     * Operation updateProjectCashNeeds
     *
     * Update an existing project_cash_needs.
     *
     *
     * @return Http response
     */
    public function updateProjectCashNeeds()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateProjectCashNeeds');
        }
        $body = $input['body'];


        return response('How about implementing updateProjectCashNeeds as a PUT method ?');
    }
    /**
     * Operation deleteProjectCashNeeds
     *
     * Deletes an project_cash_needs.
     *
     * @param int $project_cash_needs_id project_cash_needs id to delete (required)
     *
     * @return Http response
     */
    public function deleteProjectCashNeeds($project_cash_needs_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteProjectCashNeeds as a DELETE method ?');
    }
    /**
     * Operation getProjectCashNeedsById
     *
     * Find project_cash_needs by ID.
     *
     * @param int $project_cash_needs_id ID of project_cash_needs to return object (required)
     *
     * @return Http response
     */
    public function getProjectCashNeedsById($project_cash_needs_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getProjectCashNeedsById as a GET method ?');
    }
    /**
     * Operation projectCashNeedssGet
     *
     * project_cash_needss List.
     *
     *
     * @return Http response
     */
    public function projectCashNeedssGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $project_cash_needs_id = $input['project_cash_needs_id'];


        return response('How about implementing projectCashNeedssGet as a GET method ?');
    }
}
