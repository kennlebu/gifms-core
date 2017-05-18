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

        $lpo_term = new LpoTerm;


        try{


            $form = Request::only(
                        'lpo_id',
                        'terms'
                    );



            $lpo_term->lpo_id                     =   (int)       $form['lpo_id'];
            $lpo_term->terms                      =               $form['terms'];
            $lpo_term->lpo_migration_id           =     0 ;


            if($lpo_term->save()) {

                return Response()->json(array('success' => 'lpo term added','lpo_term' => $lpo_term), 200);
            }


        }catch (JWTException $e){

                return response()->json(['error'=>'You are not Authenticated'], 500);

        }

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

        $deleted_lpo_term = LpoTerm::destroy($lpo_term_id);


        if($deleted_lpo_term){
            return response()->json(['msg'=>"lpo term deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"lpo term not found"], 404,array(),JSON_PRETTY_PRINT);
        }
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
