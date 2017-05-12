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
use App\Models\LpoModels\LpoTerm;

class LPOTermApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addLpoTerm
     *
     * Add a new lpo term.
     *
     *
     * @return Http response
     */
    public function addLpoTerm()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addLpoTerm');
        }
        $body = $input['body'];


        return response('How about implementing addLpoTerm as a POST method ?');
    }
    /**
     * Operation updateLpoTerm
     *
     * Update an existing LPO Term.
     *
     *
     * @return Http response
     */
    public function updateLpoTerm()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateLpoTerm');
        }
        $body = $input['body'];


        return response('How about implementing updateLpoTerm as a PUT method ?');
    }
    /**
     * Operation deleteLpoTerm
     *
     * Deletes an lpo_term.
     *
     * @param int $lpo_term_id lpo term id to delete (required)
     *
     * @return Http response
     */
    public function deleteLpoTerm($lpo_term_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteLpoTerm as a DELETE method ?');
    }
    /**
     * Operation getLpoTermById
     *
     * Find lpo term by ID.
     *
     * @param int $lpo_term_id ID of lpo term to return object (required)
     *
     * @return Http response
     */
    public function getLpoTermById($lpo_term_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getLpoTermById as a GET method ?');
    }
    /**
     * Operation lpoTermsGet
     *
     * lpo terms List.
     *
     *
     * @return Http response
     */
    public function lpoTermsGet()
    {
        $input = Request::all();
        $response;

        //path params validation


        //not path params validation
        // $lpo_id = $input['lpo_id'];


        // return response('How about implementing lpoTermsGet as a GET method ?');
        if(array_key_exists('lpo_id', $input)){

            $response = LpoTerm::where("deleted_at",null)
                ->where('lpo_id', $input['lpo_id'])
                ->get();

        }else{

            $response = LpoTerm::all();

        }


        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}
