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
use App\Models\ProjectsModels\Project;

class ProjectApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }































    

    /**
     * Operation addProject
     *
     * Add a new project.
     *
     *
     * @return Http response
     */
    public function addProject()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addProject');
        }
        $body = $input['body'];


        return response('How about implementing addProject as a POST method ?');
    }































    
    /**
     * Operation updateProject
     *
     * Update an existing project.
     *
     *
     * @return Http response
     */
    public function updateProject()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateProject');
        }
        $body = $input['body'];


        return response('How about implementing updateProject as a PUT method ?');
    }































    
    /**
     * Operation deleteProject
     *
     * Deletes an project.
     *
     * @param int $project_id project id to delete (required)
     *
     * @return Http response
     */
    public function deleteProject($project_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteProject as a DELETE method ?');
    }































    
    /**
     * Operation getProjectById
     *
     * Find project by ID.
     *
     * @param int $project_id ID of project to return object (required)
     *
     * @return Http response
     */
    public function getProjectById($project_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getProjectById as a GET method ?');
    }































    
    /**
     * Operation projectsGet
     *
     * projects List.
     *
     *
     * @return Http response
     */
    public function projectsGet()
    {
        $input = Request::all();

        $response = Project::orderBy('project_code', 'desc')->get();

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}
