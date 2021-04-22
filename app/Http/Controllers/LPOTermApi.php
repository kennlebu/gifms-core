<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: kenlebu@gmail.com
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Models\LPOModels\LpoTerm;
use JWTAuth;
use App\Models\LPOModels\Lpo;

class LPOTermApi extends Controller
{ 
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
        $lpo_term = new LpoTerm;
        try{
            $form = Request::all();
            $is_hotel = false;

            $term_exists = LpoTerm::where('lpo_id', $form['lpo_id'])
                                ->where('terms', 'like', '\'%Day 1 to be charged as per confirmed number%\'')
                                ->first();
            if(array_key_exists('for_hotel', $form) && empty($term_exists)){
                $lpo = LPO::find($form['lpo_id']);
                if(!$this->checkVendor($lpo->preferred_supplier->id)) {
                    return response()->json(['error'=>'Supplier is disabled'], 403);
                }
                if($lpo->lpo_type == 'prenegotiated'){
                    if($lpo->supplier->supply_category_id == 1 || $lpo->supplier->supply_category_id == 2){    //Conferences (incl Accomodation), Accomodation Only
                        $is_hotel = true;
                    }
                }
                else{
                    if($lpo->preffered_quotation->supplier->supply_category_id == 1 || $lpo->preffered_quotation->supplier->supply_category_id == 2){
                        $is_hotel = true;
                    }
                }
            }

            if(array_key_exists('for_hotel', $form) && !$is_hotel){
                return Response()->json(array('success' => 'not a hotel'), 200);
            }

            $lpo_term->lpo_id                     =   (int)       $form['lpo_id'];
            $lpo_term->terms                      =               $form['terms'];
            $lpo_term->lpo_migration_id           =     0 ;


            if($lpo_term->save()) {
                $lpo = LPO::find($lpo_term->lpo_id);
                $user = JWTAuth::parseToken()->authenticate();
                activity()
                   ->performedOn($lpo)
                   ->causedBy($user)
                   ->log('added terms');

                return Response()->json(array('success' => 'lpo term added','lpo_term' => $lpo_term), 200);
            }
        }catch (JWTException $e){
                return response()->json(['error'=>'You are not Authenticated'], 401);
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
        try{
            $form = Request::all();

            $lpo = LPO::find($form['lpo_id']);
            if(!$this->checkVendor($lpo->preferred_supplier->id)) {
                return response()->json(['error'=>'Supplier is disabled'], 403);
            }

            $term = LpoTerm::findOrFail($form['id']);
            $term->lpo_id              =               $form['lpo_id'];
            $term->terms               =               $form['terms'];

            if($term->save()) {
                activity()
                   ->performedOn($lpo)
                   ->causedBy($this->current_user())
                   ->log('Terms updated');

                return Response()->json(array('success' => 'Terms updated','lpo_term' => $term), 200);
            }
        }catch (JWTException $e){
            return response()->json(['error'=>'You are not Authenticated'], 401);
        }
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
        $deleted_lpo_term = LpoTerm::destroy($lpo_term_id);
        if($deleted_lpo_term){
            return response()->json(['msg'=>"lpo term deleted"], 200);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500);
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
       try{
            $response = LpoTerm::findOrFail($lpo_term_id);
            return response()->json($response, 200);

        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500);
        }
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

        if(array_key_exists('lpo_id', $input)){

            $response = LpoTerm::where("deleted_at",null)
                ->where('lpo_id', $input['lpo_id'])
                ->get();
        }else{
            $response = LpoTerm::all();
        }
        
        return response()->json($response, 200);
    }
}
