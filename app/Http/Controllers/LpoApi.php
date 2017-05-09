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
use App\Models\LPOModels\LpoQuotation;
use App\Models\LPOModels\LpoItem;
use App\Models\LPOModels\LpoTerm;
use App\Models\LPOModels\LpoStatus;
use App\Models\SuppliesModels\Supplier;
use App\Models\StaffModels\Staff;
use App\Models\AccountingModels\Account;
use App\Models\ProjectsModels\Project;
use Exception;

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
        // if (!isset($input['body'])) {
        //     throw new \InvalidArgumentException('Missing the required parameter $body when calling add');
        // }
        // $body = $input['body'];

        // print_r($body);


        $form = request()->only(
            'requested_by_id',
            'expense_desc',
            'expense_purpose',
            'project_id',
            'currency_id',
            'quote_exempt',
            'quote_exempt_explanation',
            'preffered_supplier_id'
            );

        // $file = $request->file('requeste');

        try{

            $lpo = new Lpo;

            $lpo->requested_by_id                   =   $form['requested_by_id'];
            $lpo->expense_desc                      =   $form['expense_desc'];
            $lpo->expense_purpose                   =   $form['expense_purpose'];
            $lpo->project_id                        =   $form['project_id'];
            $lpo->currency_id                       =   $form['currency_id'];
            $lpo->quote_exempt                      =   $form['quote_exempt'];
            $lpo->quote_exempt_explanation          =   $form['quote_exempt_explanation'];
            $lpo->preffered_supplier_id             =   $form['preffered_supplier_id'];
            $lpo->status_id                         =   1;


            if($lpo->save()) {
                
                return Response()->json(array('success' => 'lpo added'), 200);

                // Save quotations
            }

        }catch (JWTException $e){

                return response()->json(['error'=>'something went wrong'], 500);

        }

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


        $deleted_lpo = lpo::destroy($lpo_id);

        print_r($deleted_lpo);

        if($deleted_lpo){
            return response()->json(['msg'=>"lpo deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"lpo not found"], 404,array(),JSON_PRETTY_PRINT);
        }

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

        try{
            
            $response = Lpo::findOrFail($lpo_id);
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"lpo could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
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
        $initial_response_data_size = 1000;
        $response_dt;

        //path params validation


        //if status is set

        if(array_key_exists('status', $input)){
            $response = Lpo::where("deleted_at",null)
                ->where('status_id', $input['status'])
                ->orderBy('chai_ref', 'desc')
                ->get();


        }else{

             $response = Lpo::where("deleted_at",null)
                ->get();
        }

        if(array_key_exists('datatables', $input)){

            $response_dt = Lpo::where("deleted_at",null)
                ->where('status_id', $input['status'])
                ->orderBy('chai_ref', 'desc')
                ->limit($input['length'])->offset($input['start'])
                ->get();

            $response_dt = $this->append_attribute_objects($response_dt);
            $response = Lpo::arr_to_dt_response( $response_dt, $input['draw'],$initial_response_data_size,sizeof($response));
        }



        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }





    public function append_attribute_objects($data = array()){



        foreach ($data as $key => $value) {

            $data[$key]["account"]              = Account::find((int) $data[$key]["account_id"]);
            $data[$key]["project_manager"]      = Staff::find((int) $data[$key]["project_manager_id"]);
            $data[$key]["received_by"]          = Staff::find((int) $data[$key]["received_by_id"]);
            $data[$key]["project"]              = Project::find((int) $data[$key]["project_id"]);
            $data[$key]["requested_by"]         = Staff::find((int) $data[$key]["requested_by_id"]);
            $data[$key]["supplier"]             = Supplier::find((int) $data[$key]["supplier_id"]);
            $data[$key]["status"]               = LpoStatus::find((int) $data[$key]["status_id"]);
            $data[$key]["quotations"]           = LpoQuotation::where("deleted_at",null)
                                                        ->where("lpo_id",$data[$key]["id"])
                                                        ->get();
            $data[$key]["items"]                = LpoItem::where("deleted_at",null)
                                                        ->where("lpo_id",$data[$key]["id"])
                                                        ->get();
            $data[$key]["terms"]                = LpoTerm::where("deleted_at",null)
                                                        ->where("lpo_id",$data[$key]["id"])
                                                        ->get();


            if($data[$key]["account"]==null){
                $data[$key]["account"] = array("account_name"=>"N/A");
            }

            if($data[$key]["project_manager"]==null){
                $data[$key]["project_manager"] = array("project_manager_name"=>"N/A");
            }

            if($data[$key]["received_by"]==null){
                $data[$key]["received_by"] = array("f_name"=>"N/A");
            }

            if($data[$key]["project"]==null){
                $data[$key]["project"] = array("Project_name"=>"N/A");
            }

            if($data[$key]["requested_by"]==null){
                $data[$key]["requested_by"] = array("f_name"=>"N/A");
            }

            if($data[$key]["supplier"]==null){
                $data[$key]["supplier"] = array("supplier_name"=>"N/A");
            }

            if($data[$key]["status"]==null){
                $data[$key]["status"] = array("lpo_status"=>"N/A");
            }
        }
        return $data;





    }
}
