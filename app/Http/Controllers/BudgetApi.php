<?php

/**
 * Budgets Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator budget.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\FinanceModels\Budget;


use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\StaffModels\Staff;

class BudgetApi extends Controller
{
    






















    /**
     * Constructor
     */
    public function __construct()
    {
    }

    






















    /**
     * Operation addBudget
     *
     * Add a new budget.
     *
     *
     * @return Http response
     */
    public function addBudget()
    {
        $form = Request::only(
            'budget_desc',
            'currency_id',
            'start_date',
            'end_date'
            );

        $budget = new Budget;

            $budget->budget_desc                  =         $form['budget_desc'];
            $budget->currency_id                  = (int)   $form['currency_id'];
            $budget->start_date                   =         $form['start_date'];
            $budget->end_date                     =         $form['end_date'];
            $budget->created_by_id                = (int)   $this->current_user()->id;
            $budget->create_action_by_id          = (int)   $this->current_user()->id;

        if($budget->save()) {

            return Response()->json(array('msg' => 'Success: budget added','budget' => $budget), 200);
        }
    }
    






















    /**
     * Operation updateBudget
     *
     * Update an existing budget.
     *
     *
     * @return Http response
     */
    public function updateBudget()
    {
        $form = Request::only(
            'id',
            'budget_desc',
            'currency_id',
            'donor_id',
            'start_date',
            'end_date'
            );

        $budget = Budget::find($form['id']);

            $budget->budget_desc                  =         $form['budget_desc'];
            $budget->currency_id                  = (int)   $form['currency_id'];
            $budget->start_date                   =         $form['start_date'];
            $budget->end_date                     =         $form['end_date'];

        if($budget->save()) {

            return Response()->json(array('msg' => 'Success: budget updated','budget' => $budget), 200);
        }
    }
    






















    /**
     * Operation deleteBudget
     *
     * Deletes an budget.
     *
     * @param int $budget_id budget id to delete (required)
     *
     * @return Http response
     */
    public function deleteBudget($budget_id)
    {
        $input = Request::all();


        $deleted = Budget::destroy($budget_id);

        if($deleted){
            return response()->json(['msg'=>"budget deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"budget not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    






















    /**
     * Operation getBudgetById
     *
     * Find budget by ID.
     *
     * @param int $budget_id ID of budget to return object (required)
     *
     * @return Http response
     */
    public function getBudgetById($budget_id)
    {
        $input = Request::all();

        try{

            $response   = Budget::findOrFail($budget_id);
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"budget could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
    






















    /**
     * Operation budgetsGet
     *
     * Budgets List.
     *
     *
     * @return Http response
     */
    public function budgetsGet()
    {
       
        $input = Request::all();
        //query builder
        $qb = DB::table('budgets');

        $qb->whereNull('budgets.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;




        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('budgets.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('budgets.budget_desc','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Budget::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
            // $records_filtered = 30;


        }


        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "desc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $qb->orderBy($order_column_name, $order_direction);
        }else{
            //$qb->orderBy("project_code", "asc");
        }

        //limit
        if(array_key_exists('limit', $input)){


            $qb->limit($input['limit']);


        }

        //migrated
        if(array_key_exists('migrated', $input)){

            $mig = (int) $input['migrated'];

            if($mig==0){
                $qb->whereNull('migration_id');
            }else if($mig==1){
                $qb->whereNotNull('migration_id');
            }


        }



        if(array_key_exists('datatables', $input)){

            //searching
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('budgets.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('budgets.budget_desc','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = Budget::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];


            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){

                $qb->orderBy($order_column_name, $order_direction);

            }






            //limit $ offset
            if((int)$input['start']!= 0 ){

                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);

            }else{
                $qb->limit($input['length']);
            }





            $sql = Budget::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Budget::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = Budget::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }




















    public function append_relationships_objects($data = array()){


        foreach ($data as $key => $value) {

            $budgets = Budget::find($data[$key]['id']);

            $data[$key]['items']                = $budgets->items;
            $data[$key]['currency']              = $budgets->currency;

        }


        return $data;


    }










    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            if($data[$key]["currency"]==null){
                $data[$key]["currency"] = array("currency_name"=>"N/A");
            }


        }

        return $data;


    }
}
