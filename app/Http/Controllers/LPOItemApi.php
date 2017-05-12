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
use App\Models\LpoModels\LpoItem;

class LPOItemApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addLpoItem
     *
     * Add a new lpo item.
     *
     *
     * @return Http response
     */
    public function addLpoItem()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addLpoItem');
        }
        $body = $input['body'];


        return response('How about implementing addLpoItem as a POST method ?');
    }
    /**
     * Operation updateLpoItem
     *
     * Update an existing LPO Item.
     *
     *
     * @return Http response
     */
    public function updateLpoItem()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateLpoItem');
        }
        $body = $input['body'];


        return response('How about implementing updateLpoItem as a PUT method ?');
    }
    /**
     * Operation deleteLpoItem
     *
     * Deletes an lpo_item.
     *
     * @param int $lpo_item_id lpo item id to delete (required)
     *
     * @return Http response
     */
    public function deleteLpoItem($lpo_item_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteLpoItem as a DELETE method ?');
    }
    /**
     * Operation getLpoItemById
     *
     * Find lpo item by ID.
     *
     * @param int $lpo_item_id ID of lpo item to return object (required)
     *
     * @return Http response
     */
    public function getLpoItemById($lpo_item_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getLpoItemById as a GET method ?');
    }
    /**
     * Operation lpoItemsGet
     *
     * lpo items List.
     *
     *
     * @return Http response
     */
    public function lpoItemsGet()
    {
        $input = Request::all();
        $response;

        //path params validation


        //not path params validation
        // $lpo_id = $input['lpo_id'];


        // return response('How about implementing lpoTermsGet as a GET method ?');
        if(array_key_exists('lpo_id', $input)){

            $response = LpoItem::where("deleted_at",null)
                ->where('lpo_id', $input['lpo_id'])
                ->get();

        }else{

            $response = LpoItem::all();

        }


        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}
