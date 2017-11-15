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
use App\Models\BankingModels\Bank;

class BankApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation addBank
     *
     * Add a new bank.
     *
     *
     * @return Http response
     */
    public function addBank()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling addBank');
        }
        $body = $input['body'];


        return response('How about implementing addBank as a POST method ?');
    }
    /**
     * Operation updateBank
     *
     * Update an existing bank.
     *
     *
     * @return Http response
     */
    public function updateBank()
    {
        $input = Request::all();

        //path params validation


        //not path params validation
        if (!isset($input['body'])) {
            throw new \InvalidArgumentException('Missing the required parameter $body when calling updateBank');
        }
        $body = $input['body'];


        return response('How about implementing updateBank as a PUT method ?');
    }
    /**
     * Operation deleteBank
     *
     * Deletes an bank.
     *
     * @param int $bank_id bank id to delete (required)
     *
     * @return Http response
     */
    public function deleteBank($bank_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing deleteBank as a DELETE method ?');
    }
    /**
     * Operation getBankById
     *
     * Find bank by ID.
     *
     * @param int $bank_id ID of bank to return object (required)
     *
     * @return Http response
     */
    public function getBankById($bank_id)
    {
        $input = Request::all();

        //path params validation


        //not path params validation

        return response('How about implementing getBankById as a GET method ?');
    }
    /**
     * Operation banksGet
     *
     * banks List.
     *
     *
     * @return Http response
     */
    public function banksGet()
    {
        $input = Request::all();
        $response;        

        $response = Bank::all();

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}
