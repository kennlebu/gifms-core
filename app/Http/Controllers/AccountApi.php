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

use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\AccountingModels\Account;
use App\Models\AccountingModels\AccountClassification;
use App\Models\AccountingModels\AccountType;

use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\StaffModels\Staff;

class AccountApi extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {
    }































    

    /**
     * Operation addAccount
     *
     * Add a new account.
     *
     *
     * @return Http response
     */
    public function addAccount()
    {
        $form = Request::only(
            'account_code',
            'account_name',
            'account_desc',
            'account_format',
            'office_cost',
            'account_type'
            );

        $account = new Account;

            $account->account_code                     =         $form['account_code'];
            $account->account_name                     =         $form['account_name'];
            $account->account_desc                     =         $form['account_desc'];
            $account->account_format                   =         $form['account_format'];
            $account->office_cost                      =  (int)  $form['office_cost'];
            $account->account_type                     =  (int)  $form['account_type'];

        if($account->save()) {

            return Response()->json(array('msg' => 'Success: account added','account' => $account), 200);
        }
    }































    
    /**
     * Operation updateAccount
     *
     * Update an existing account.
     *
     *
     * @return Http response
     */
    public function updateAccount()
    {
        $form = Request::only(
            'id',
            'account_code',
            'account_name',
            'account_desc',
            'account_format',
            'account_type'
            );

        $account = Account::find($form['id']);

            $account->account_code                     =         $form['account_code'];
            $account->account_name                     =         $form['account_name'];
            $account->account_desc                     =         $form['account_desc'];
            $account->account_format                   =         $form['account_format'];
            $account->office_cost                      =  (int)  $form['office_cost'];
            $account->account_type                     =  (int)  $form['account_type'];

        if($account->save()) {

            return Response()->json(array('msg' => 'Success: account updated','account' => $account), 200);
        }
    }































    
    /**
     * Operation deleteAccount
     *
     * Deletes an account.
     *
     * @param int $account_id account id to delete (required)
     *
     * @return Http response
     */
    public function deleteAccount($account_id)
    {
        $input = Request::all();


        $deleted = Account::destroy($account_id);

        if($deleted){
            return response()->json(['msg'=>"account deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"account not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }































    
    /**
     * Operation getAccountById
     *
     * Find account by ID.
     *
     * @param int $account_id ID of account to return object (required)
     *
     * @return Http response
     */
    public function getAccountById($account_id)
    {
        $input = Request::all();

        try{

            $response   = Account::findOrFail($account_id);
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"account could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }































    
    /**
     * Operation accountsGet
     *
     * accounts List.
     *
     *
     * @return Http response
     */
    public function accountsGet()
    {
        


        $input = Request::all();
        //query builder
        $qb = DB::table('accounts');

        $qb->whereNull('accounts.deleted_at');
        $current_user = JWTAuth::parseToken()->authenticate();

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        
        //my_assigned
        if((array_key_exists('my_assigned', $input)&& $input['my_assigned'] = "true")&&($current_user->hasRole(['accountant','assistant-accountant','financial-controller']))){

            $qb->orderBy('account_code', 'desc');
        }elseif (array_key_exists('my_assigned', $input)&& $input['my_assigned'] = "true") {

            $qb->select(DB::raw('accounts.*'))
                 ->rightJoin('account_teams', 'account_teams.account_id', '=', 'accounts.id')
                 ->rightJoin('staff', 'staff.id', '=', 'account_teams.staff_id')
                 ->where('staff.id', '=', $current_user->id)
                 ->groupBy('accounts.id')
                 ->orderBy('accounts.account_code', 'desc');
        }


        //query builder
        $qb = DB::table('accounts');

        if(array_key_exists('account_format', $input)){
            $qb->where('account_format', $input['account_format']);
        }


        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('accounts.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('accounts.account_name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('accounts.account_desc','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Account::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
            // $records_filtered = 30;


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
                
                $query->orWhere('accounts.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('accounts.account_name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('accounts.account_desc','like', '\'%' . $input['search']['value']. '%\'');

            });




            $sql = Account::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = Account::bind_presql($qb->toSql(),$qb->getBindings());

            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Account::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = Account::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            $response       = $this->append_relationships_objects($response);
            $response       = $this->append_relationships_nulls($response);
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }




















    public function append_relationships_objects($data = array()){


        foreach ($data as $key => $value) {

            $accounts = Account::find($data[$key]['id']);

            $data[$key]['account_type']                  = $accounts->account_type;

        }


        return $data;


    }










    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            if($data[$key]["account_type"]==null){
                $data[$key]["account_type"] = array("account_type_name"=>"N/A");
            }


        }

        return $data;


    }
}
