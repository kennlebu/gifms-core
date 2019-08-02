<?php

/**
 * BudgetItems Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator budget_item.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\FinanceModels\BudgetItem;
use Exception;

class BudgetItemApi extends Controller
{
    /**
     * Operation addBudgetItem
     *
     * Add a new budget_item.
     *
     *
     * @return Http response
     */
    public function addBudgetItem()
    {
        $form = Request::all();

        $budget_item = new BudgetItem;
        $budget_item->budget_id = (int) $form['budget_id'];
        $budget_item->objective_id = (int) $form['objective_id'];
        $budget_item->amount = (double) $form['amount'];
        $budget_item->created_by_id = (int) $this->current_user()->id;
        $budget_item->create_action_by_id = (int) $this->current_user()->id;

        if($budget_item->save()) {
            return Response()->json(array('msg' => 'Success: budget_item added','budget_item' => $budget_item), 200);
        }
    }
    






















    /**
     * Operation updateBudgetItem
     *
     * Update an existing budget_item.
     *
     *
     * @return Http response
     */
    public function updateBudgetItem()
    {
        $form = Request::all();

        $budget_item = BudgetItem::find($form['id']);
        $budget_item->budget_id = (int) $form['budget_id'];
        $budget_item->objective_id = (int) $form['objective_id'];
        $budget_item->amount = (double) $form['amount'];

        if($budget_item->save()) {
            return Response()->json(array('msg' => 'Success: budget_item updated','budget_item' => $budget_item), 200);
        }
    }
    






















    /**
     * Operation deleteBudgetItem
     *
     * Deletes an budget_item.
     *
     * @param int $budget_item_id budget_item id to delete (required)
     *
     * @return Http response
     */
    public function deleteBudgetItem($budget_item_id)
    {
        $deleted = BudgetItem::destroy($budget_item_id);
        if($deleted){
            return response()->json(['msg'=>"budget_item deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"budget_item not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    






















    /**
     * Operation getBudgetItemById
     *
     * Find budget_item by ID.
     *
     * @param int $budget_item_id ID of budget_item to return object (required)
     *
     * @return Http response
     */
    public function getBudgetItemById($budget_item_id)
    {
        try{
            $response   = BudgetItem::with('objective')->findOrFail($budget_item_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){
            $response =  ["error"=>"budget_item could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
    






















    /**
     * Operation budgetItemsGet
     *
     * BudgetItems List.
     *
     *
     * @return Http response
     */
    public function budgetItemsGet()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('budget_items');

        $qb->whereNull('budget_items.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                $query->orWhere('budget_items.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('budget_items.budget_item_purpose','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = BudgetItem::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
        }


        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }
            $qb->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $qb->limit($input['limit']);
        }

        if(array_key_exists('datatables', $input)){
            //searching
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('budget_items.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('budget_items.budget_item_purpose','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = BudgetItem::bind_presql($qb->toSql(),$qb->getBindings());
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

            $sql = BudgetItem::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);
            $response       = BudgetItem::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }else{

            $sql            = BudgetItem::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}
