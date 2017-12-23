<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator staff.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;


use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;


use Exception;
use App;
use Illuminate\Support\Facades\Response;
use App\Models\StaffModels\Staff;
use Config;

class StaffApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    




















    /**
     * Operation addStaff
     *
     * Add a new staff.
     *
     *
     * @return Http response
     */
    public function addStaff()
    {
        $form = Request::only(
            'username',
            'email',
            'password',
            'old_password',
            'security_group_id',
            'f_name',
            'l_name',
            'o_names',
            'department_id',
            'official_post',
            'mobile_no',
            'mpesa_no',
            'bank_account',
            'cheque_addressee',
            'payment_mode_id',
            'bank_id',
            'bank_branch_id',
            'station',
            'swift_code',
            'active',
            'receives_global_bccs',
            'signature',
            'bank_signatory',
            'receive_global_email_bcc'
            );

        $staff = new Staff;

            $default_pwd        = Config::get('app.default_password');
            $encr_pwd           = bcrypt($default_pwd);
            $encr_old_pwd       = $this->encrypt_password( $default_pwd);

            $staff->username                     =         $form['username'];
            $staff->email                        =         $form['email'];

            $staff->password                     =         $encr_pwd;
            $staff->old_password                 =         $encr_old_pwd;

            $staff->security_group_id            =  (int)  $form['security_group_id'];
            $staff->f_name                       =         $form['f_name'];
            $staff->l_name                       =         $form['l_name'];
            $staff->o_names                      =         $form['o_names'];
            $staff->department_id                =  (int)  $form['department_id'];
            $staff->official_post                         =         $form['official_post'];
            $staff->mobile_no                    =         $form['mobile_no'];
            $staff->mpesa_no                     =         $form['mpesa_no'];
            $staff->bank_account                 =         $form['bank_account'];
            $staff->cheque_addressee             =         $form['cheque_addressee'];
            $staff->payment_mode_id              =  (int)  $form['payment_mode_id'];
            $staff->bank_id                      =  (int)  $form['bank_id'];
            $staff->bank_branch_id               =  (int)  $form['bank_branch_id'];
            $staff->station                      =         $form['station'];
            $staff->swift_code                   =         $form['swift_code'];
            $staff->active                       =         $form['active'];
            $staff->receives_global_bccs         =         $form['receives_global_bccs'];
            $staff->signature                    =         $form['signature'];
            $staff->bank_signatory               =         $form['bank_signatory'];
            $staff->receive_global_email_bcc     =         $form['receive_global_email_bcc'];

        if($staff->save()) {

            return Response()->json(array('msg' => 'Success: staff added','staff' => $staff), 200);
        }
    }
    




















    /**
     * Operation updateStaff
     *
     * Update an existing staff.
     *
     *
     * @return Http response
     */
    public function updateStaff()
    {
        $form = Request::only(
            'id',
            'username',
            'email',
            'password',
            'old_password',
            'security_group_id',
            'f_name',
            'l_name',
            'o_names',
            'department_id',
            'official_post',
            'mobile_no',
            'mpesa_no',
            'bank_account',
            'cheque_addressee',
            'payment_mode_id',
            'bank_id',
            'bank_branch_id',
            'station',
            'swift_code',
            'active',
            'receives_global_bccs',
            'signature',
            'bank_signatory',
            'receive_global_email_bcc'
            );

        $staff = Staff::find($form['id']);

            $staff->username                     =         $form['username'];
            $staff->email                        =         $form['email'];
            // $staff->password                     =         $form['password'];
            // $staff->old_password                 =         $form['old_password'];
            $staff->security_group_id            =  (int)  $form['security_group_id'];
            $staff->f_name                       =         $form['f_name'];
            $staff->l_name                       =         $form['l_name'];
            $staff->o_names                      =         $form['o_names'];
            $staff->department_id                =  (int)  $form['department_id'];
            $staff->official_post                         =         $form['official_post'];
            $staff->mobile_no                    =         $form['mobile_no'];
            $staff->mpesa_no                     =         $form['mpesa_no'];
            $staff->bank_account                 =         $form['bank_account'];
            $staff->cheque_addressee             =         $form['cheque_addressee'];
            $staff->payment_mode_id              =  (int)  $form['payment_mode_id'];
            $staff->bank_id                      =  (int)  $form['bank_id'];
            $staff->bank_branch_id               =  (int)  $form['bank_branch_id'];
            $staff->station                      =         $form['station'];
            $staff->swift_code                   =         $form['swift_code'];
            $staff->active                       =         $form['active'];
            $staff->receives_global_bccs         =         $form['receives_global_bccs'];
            $staff->signature                    =         $form['signature'];
            $staff->bank_signatory               =         $form['bank_signatory'];
            $staff->receive_global_email_bcc     =         $form['receive_global_email_bcc'];

        if($staff->save()) {

            return Response()->json(array('msg' => 'Success: staff updated','staff' => $staff), 200);
        }
    }
    




















    /**
     * Operation deleteStaff
     *
     * Deletes an staff.
     *
     * @param int $staff_id staff id to delete (required)
     *
     * @return Http response
     */
    public function deleteStaff($staff_id)
    {
        $input = Request::all();


        $deleted = Staff::destroy($staff_id);

        if($deleted){
            return response()->json(['msg'=>"staff deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"staff not found"], 404,array(),JSON_PRETTY_PRINT);
        }
    }
    
    



















    /**

     * Operation getStaffById
     *
     * Find staff by ID.
     *
     * @param int $staff_id ID of staff to return object (required)
     *
     * @return Http response
     */
    public function getStaffById($staff_id)
    {
        $input = Request::all();

        try{

            $response   = Staff::with("roles")->findOrFail($staff_id);
           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"staff could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }
    }
























    
    /**
     * Operation updateStaffRoles
     *
     * Updates Staff Roles by ID.
     *
     * @param int $staff_id ID of staff to return object (required)
     *
     * @return Http response
     */
    public function updateStaffRoles($staff_id)
    {
        $form = Request::only(
            'roles',
            );        

        try{
            $staff  =   Staff::findOrFail($staff_id);
            $staff->roles()->sync($form->roles);
            $response   = Staff::with("roles")->findOrFail($staff_id);
           
            return response()->json(['msg'=>"Roles Updated", 'staff'=>$response], 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){

            $response =  ["error"=>"staff could not be found"];
            return response()->json($response, 404,array(),JSON_PRETTY_PRINT);
        }

    }
    




















    /**
     * Operation staffsGet
     *
     * staffs List.
     *
     *
     * @return Http response
     */
    public function staffsGet()
    {
        
        // ini_set('memory_limit', '2048M');

        $input = Request::all();
        //query builder
        $qb = DB::table('staff');

        $qb->whereNull('staff.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;




        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {
                
                $query->orWhere('staff.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('staff.f_name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('staff.l_name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('staff.o_names','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('staff.email','like', '\'%' . $input['searchval']. '%\'');

            });

            // $records_filtered       =  $qb->count(); //doesn't work

            $sql = Staff::bind_presql($qb->toSql(),$qb->getBindings());
            $sql = str_replace("*"," count(*) AS count ", $sql);
            $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = (int) $dt[0]['count'];
            // $records_filtered = 30;


        }


        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }

            $qb->orderBy($order_column_name, $order_direction);
        }else{
            // $qb->orderBy("f_name", "asc");
        }

        //limit
        if(array_key_exists('limit', $input)){


            $qb->limit($input['limit']);


        }
        //roles
        if(array_key_exists('role_abr', $input)){
            // $qb->whereHas('roles', function ($query) use ($input){
            //     $query->where('acronym', $input['role_abr']);
            // });


            $qb->select(DB::raw('staff.*'))
                 ->leftJoin('user_roles', 'user_roles.user_id', '=', 'staff.id')
                 ->leftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
                 ->where('roles.acronym', '=', "'".$input['role_abr']."'")
                 ->groupBy('staff.id');
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
                
                $query->orWhere('staff.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('staff.username','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('staff.email','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('staff.f_name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('staff.l_name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('staff.o_names','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('staff.official_post','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('staff.mobile_no','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('staff.mpesa_no','like', '\'%' . $input['search']['value']. '%\'');


            });




            $sql = Staff::bind_presql($qb->toSql(),$qb->getBindings());
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





            $sql = Staff::bind_presql($qb->toSql(),$qb->getBindings());
            // echo $sql;die;
            // $response_dt = DB::select($qb->toSql(),$qb->getBindings());         //pseudo
            $response_dt = DB::select($sql);


            $response_dt = json_decode(json_encode($response_dt), true);

            $response_dt    = $this->append_relationships_objects($response_dt);
            $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Staff::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );


        }else{

            $sql            = Staff::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
                $response       = $this->append_relationships_nulls($response);
            }
        }




        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);


    }




















    public function append_relationships_objects($data = array()){

        $input = Request::all();


        foreach ($data as $key => $value) {

            
            $staff = Staff::find($data[$key]['id']);

            //with_assigned_projects
            if(array_key_exists('detailed', $input)&& $input['detailed'] = "true"){
                $data[$key]['detailed']        =   $staff->assigned_projects;
                $data[$key]['roles']                    =   $staff->roles;
                $data[$key]['programs']                 =   $staff->programs;
            }


            $data[$key]['department']               =   $staff->department;
            $data[$key]['payment_mode']             =   $staff->payment_mode;
            $data[$key]['bank']                     =   $staff->bank;
            $data[$key]['bank_branch']              =   $staff->bank_branch;

        }


        return $data;


    }










    



    public function append_relationships_nulls($data = array()){


        foreach ($data as $key => $value) {


            if($data[$key]["department"]==null){
                $data[$key]["department"] = array("department_name"=>"N/A");
            }
            if($data[$key]["payment_mode"]==null){
                $data[$key]["payment_mode"] = array("payment_mode_description"=>"N/A");
            }
            if($data[$key]["bank"]==null){
                $data[$key]["bank"] = array("bank_name"=>"N/A");
            }
            if($data[$key]["bank_branch"]==null){
                $data[$key]["bank_branch"] = array("branch_name"=>"N/A");
            }


        }

        return $data;


    }


    public function encrypt_password($password_str){
        $offset = 8;
        $encrypted_password = '';
        for ($i = 1; $i <= strlen($password_str); $i++) 
        {
            $encrypted_password.=chr((ord(substr($password_str,$i-1,1)) + $offset));
        }
        return $encrypted_password;
    }
    public function decrypt_password($password_str){
        $offset = 8;
        $decrypted_password = '';
        for ($i = 1; $i <= strlen($password_str); $i++) 
        {
            $decrypted_password.=chr((ord(substr($password_str,$i-1,1)) - $offset));
        }
        return $decrypted_password;
    }
}
