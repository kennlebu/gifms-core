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

class ProjectBudgetAccountApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }


































    

    /**
     * Operation addProjectBudgetAccount
     *
     * Add a new project_budget_account.
     *
     *
     * @return Http response
     */
    public function addProjectBudgetAccount()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addProjectBudgetAccount');
        }
        $body = $input['body'];


        return response('How about implementing addProjectBudgetAccount as a POST method ?');
    }


































    
    /**
     * Operation updateProjectBudgetAccount
     *
     * Update an existing project_budget_account.
     *
     *
     * @return Http response
     */
    public function updateProjectBudgetAccount()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateProjectBudgetAccount');
        }
        $body = $input['body'];


        return response('How about implementing updateProjectBudgetAccount as a PUT method ?');
    }


































    
    /**
     * Operation deleteProjectBudgetAccount
     *
     * Deletes an project_budget_account.
     *
     * @param int $project_budget_account_id project_budget_account id to delete (required)
     *
     * @return Http response
     */
    public function deleteProjectBudgetAccount($project_budget_account_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteProjectBudgetAccount as a DELETE method ?');
    }


































    
    /**
     * Operation getProjectBudgetAccountById
     *
     * Find project_budget_account by ID.
     *
     * @param int $project_budget_account_id ID of project_budget_account to return object (required)
     *
     * @return Http response
     */
    public function getProjectBudgetAccountById($project_budget_account_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getProjectBudgetAccountById as a GET method ?');
    }


































    
    /**
     * Operation projectBudgetAccountsGet
     *
     * project_budget_accounts List.
     *
     *
     * @return Http response
     */
    public function projectBudgetAccountsGet()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        $project_budget_account_id = $input['project_budget_account_id'];


        return response('How about implementing projectBudgetAccountsGet as a GET method ?');
    }
}
