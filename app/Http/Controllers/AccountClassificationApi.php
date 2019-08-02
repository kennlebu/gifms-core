<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator account_classification.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\AccountingModels\AccountClassification;
use Exception;

class AccountClassificationApi extends Controller
{
    /**
     * Operation addAccountClassification
     *
     * Add a new account_classification.
     * @return Http response
     */
    public function addAccountClassification()
    {
        $form = Request::only(
            'account_classification_name'
            );

        $account_classification = new AccountClassification;
        $account_classification->account_classification_name = $form['account_classification_name'];

        if($account_classification->save()) {
            return Response()->json(array('msg' => 'Success: account_classification added','account_classification' => $account_classification), 200);
        }
    }
    




















    /**
     * Operation updateAccountClassification
     *
     * Update an existing account_classification.
     *
     *
     * @return Http response
     */
    public function updateAccountClassification()
    {
        $form = Request::only(
            'id',
            'account_classification_name'
            );

        $account_classification = AccountClassification::find($form['id']);
        $account_classification->account_classification_name = $form['account_classification_name'];

        if($account_classification->save()) {
            return Response()->json(array('msg' => 'Success: account_classification updated','account_classification' => $account_classification), 200);
        }
    }
    




















    /**
     * Operation deleteAccountClassification
     *
     * Deletes an account_classification.
     *
     * @param int $account_classification_id account_classification id to delete (required)
     *
     * @return Http response
     */
    public function deleteAccountClassification($account_classification_id)
    {
        $deleted = AccountClassification::destroy($account_classification_id);
        if($deleted){
            return response()->json(['msg'=>"account_classification deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }
    
    



















    /**

     * Operation getAccountClassificationById
     *
     * Find account_classification by ID.
     *
     * @param int $account_classification_id ID of account_classification to return object (required)
     *
     * @return Http response
     */
    public function getAccountClassificationById($account_classification_id)
    {
        try{
            $response   = AccountClassification::findOrFail($account_classification_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){
            $response =  ["error"=>"account_classification could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
    




















    /**
     * Operation accountClassificationsGet
     *
     * account_classifications List.
     *
     *
     * @return Http response
     */
    public function accountClassificationsGet()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('account_classifications');

        $qb->whereNull('account_classifications.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('account_classifications.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('account_classifications.account_classification_name','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = AccountClassification::bind_presql($qb->toSql(),$qb->getBindings());
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
                $query->orWhere('account_classifications.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('account_classifications.account_classification_name','like', '\'%' . $input['search']['value']. '%\'');
            });

            $sql = AccountClassification::bind_presql($qb->toSql(),$qb->getBindings());
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

            $sql = AccountClassification::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);
            $response       = AccountClassification::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }else{
            $sql            = AccountClassification::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
                $response       = $this->append_relationships_nulls($response);
            }
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }
}
