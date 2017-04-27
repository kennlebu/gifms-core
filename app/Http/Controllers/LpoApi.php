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
use App\Models\LPOModels\Lpo;

class LpoApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation add
     *
     * Add a new lpo request to the store.
     *
     *
     * @return Http response
     */
    public function add()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling add');
        }
        $body = $input['body'];


        return response('How about implementing add as a POST method ?');
    }
    /**
     * Operation updateLpo
     *
     * Update an existing LPO.
     *
     *
     * @return Http response
     */
    public function updateLpo()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateLpo');
        }
        $body = $input['body'];


        return response('How about implementing updateLpo as a PUT method ?');
    }
    /**
     * Operation deleteLpo
     *
     * Deletes an lpo.
     *
     * @param int $lpo_id lpo id to delete (required)
     *
     * @return Http response
     */
    public function deleteLpo($lpo_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteLpo as a DELETE method ?');
    }
    /**
     * Operation getLpoById
     *
     * Find lpo by ID.
     *
     * @param int $lpo_id ID of lpo to return lpo object (required)
     *
     * @return Http response
     */
    public function getLpoById($lpo_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getLpoById as a GET method ?');
    }
    /**
     * Operation updateLpoWithForm
     *
     * Updates a lpo with form data.
     *
     * @param int $lpo_id ID of lpo that needs to be updated (required)
     *
     * @return Http response
     */
    public function updateLpoWithForm($lpo_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing updateLpoWithForm as a POST method ?');
    }



















    
    /**
     * Operation lposGet
     *
     * lpo List.
     *
     *
     * @return Http response
     */
    public function lposGet()
    {
        $input = Request::all();
        $response;

        //path params validation


        //if status is set

        if(array_key_exists('status', $input)){

            $response = Lpo::where("deleted_at",null)
                ->where('status', $input['status'])
                ->orderBy('chai_ref', 'desc')
                ->get();

        }else{

             $response = Lpo::where("deleted_at",null)
                ->orderBy('chai_ref', 'desc')
                ->get();
        }


           


            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }
}
