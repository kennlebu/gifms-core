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
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Models\StaffModels\Staff;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyNewStaff;
use App\Models\ProgramModels\ProgramStaff;

class StaffApi extends Controller
{
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
            'gender',
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

        $default_pwd        = strtolower($form['f_name']);
        $encr_pwd           = bcrypt($default_pwd);

        $staff->username                     =         $form['username'];
        $staff->email                        =         $form['email'];
        $staff->password                     =         $encr_pwd;
        $staff->security_group_id            =  (int)  $form['security_group_id'];
        $staff->f_name                       =         $form['f_name'];
        $staff->l_name                       =         $form['l_name'];
        $staff->o_names                      =         $form['o_names'];
        $staff->department_id                =  (int)  $form['department_id'];
        $staff->official_post                =         $form['official_post'];
        $staff->gender                       =         $form['gender'];
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
            // Send email with password to the new user
            Mail::queue(new NotifyNewStaff($staff, $default_pwd));
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
            'gender',
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
            $staff->security_group_id            =  (int)  $form['security_group_id'];
            $staff->f_name                       =         $form['f_name'];
            $staff->l_name                       =         $form['l_name'];
            $staff->o_names                      =         $form['o_names'];
            $staff->department_id                =  (int)  $form['department_id'];
            $staff->official_post                =         $form['official_post'];
            $staff->gender                       =         $form['gender'];
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
        $deleted = Staff::destroy($staff_id);
        if($deleted){
            return response()->json(['msg'=>"staff deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
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
        try{
            $response   = Staff::with("roles")->findOrFail($staff_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
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
            'roles'
            );        

        try{
            $staff  =   Staff::findOrFail($staff_id);
            $staff->roles()->sync($form->roles);
            $response   = Staff::with("roles")->findOrFail($staff_id);
           
            return response()->json(['msg'=>"Roles Updated", 'staff'=>$response], 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
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
        $input = Request::all();
        //query builder
        $qb = Staff::query();
        if(!array_key_exists('lean', $input)){
            $qb = Staff::with('roles','programs','department','payment_mode','bank','bank_branch');
        }

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb = $qb->where(function ($query) use ($input) {                
                $query->orWhere('staff.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('staff.f_name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('staff.l_name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('staff.o_names','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('staff.email','like', '\'%' . $input['searchval']. '%\'');
            });

            $qb = $qb->count();
        }

        //getting by id
        if(array_key_exists('staff_id', $input)){
            $qb = $qb->where(function ($query) use ($input) {                
                $query->where('staff.id','=', $input['staff_id']);
            });
        }

        //ordering
        if(array_key_exists('order_by', $input)&&$input['order_by']!=''){
            $order_direction     = "asc";
            $order_column_name   = $input['order_by'];
            if(array_key_exists('order_dir', $input)&&$input['order_dir']!=''){                
                $order_direction = $input['order_dir'];
            }
            $qb = $qb->orderBy($order_column_name, $order_direction);
        }

        //limit
        if(array_key_exists('limit', $input)){
            $qb = $qb->limit($input['limit']);
        }
        //roles
        if(array_key_exists('role_abr', $input)){
            $qb = $qb->whereHas('roles', function($query) use ($input){
                $query->where('acronym', '=', "'".$input['role_abr']."'");  
            });  

            // $qb->select(DB::raw('staff.*'))
            //      ->leftJoin('user_roles', 'user_roles.user_id', '=', 'staff.id')
            //      ->leftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
            //      ->where('roles.acronym', '=', "'".$input['role_abr']."'")
            //      ->groupBy('staff.id');
        }

        //PMs for a user OR line managers for leave requests
        if(array_key_exists('my_pms', $input) || array_key_exists('line_managers', $input)){
            //select the projects of the user
            $user = JWTAuth::parseToken()->authenticate();
            $admin_role = Staff::whereHas('roles', function($query) use ($input){
                $arr = [1,2,3,4,5,6,8,9,10,11];
                if(array_key_exists('line_managers', $input)){
                    $arr = [1,2,3,4,5,6,10,11];
                }
                $query->whereIn('role_id', $arr);
            })->where('id', $user->id)->get();

            // Get only user PMs if user doesn't have admin role
            if((count($admin_role)<=0) || array_key_exists('for_requester', $input)){
                $uid = $user->id;
                if(array_key_exists('for_requester', $input)){
                    $uid = $input['for_requester'];
                }
                $program_teams = ProgramStaff::with('program.managers')->where('staff_id', $uid)->get();

                $program_managers = array();
                
                foreach($program_teams as $team){
                    array_push($program_managers, $team->program->managers->program_manager_id);
                }

                $qb = $qb->whereIn('id',$program_managers)->groupBy('id');
            }

            // Get only directors for PMs and above if it's line managers required
            else if(array_key_exists('line_managers', $input)){
                if($user->hasRole(['program-manager','financial-controller','admin-manager','director'])){
                    // $qb->select(DB::raw('staff.*'))
                    // ->leftJoin('user_roles', 'user_roles.user_id', '=', 'staff.id')
                    // ->leftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
                    // ->where('roles.acronym', '=', "'dir'")
                    // ->orWhere('roles.acronym', '=', "'a-dir'")
                    // ->groupBy('staff.id');

                    $qb = $qb->whereHas('roles', function($query) use ($input){
                        $query->whereIn('acronym', '=', ['dir','a-dir']);  
                    });  
                }
                else{
                    $program_teams = ProgramStaff::with('program.managers')->where('staff_id', $user->id)->get();
                    $program_managers = array();                    
                    foreach($program_teams as $team){
                        array_push($program_managers, $team->program->managers->program_manager_id);
                    }

                    $qb = $qb->whereIn('id',$program_managers)->groupBy('id');
                }
            }

            // Get all PMs for administrative staff
            else{
                // $qb->select(DB::raw('staff.*'))
                //  ->leftJoin('user_roles', 'user_roles.user_id', '=', 'staff.id')
                //  ->leftJoin('roles', 'roles.id', '=', 'user_roles.role_id')
                //  ->where('roles.acronym', '=', "'pm'")
                //  ->groupBy('staff.id');

                $qb = $qb->whereHas('roles', function($query) use ($input){
                    $query->where('acronym', '=', 'pm');  
                }); 
            }
        }

        if(array_key_exists('peer_pms', $input)){
            $qb = $qb->whereHas('roles', function($query) use ($input){
                $query->where('acronym', '=', 'pm');  
            }); 
        }

        if(array_key_exists('datatables', $input)){
            //searching
            if(!empty($input['search']['value'])){
                $qb = $qb->where(function ($query) use ($input) {                
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
            }

            // $sql = Staff::bind_presql($qb->toSql(),$qb->getBindings());
            // $sql = str_replace("*"," count(*) AS count ", $sql);
            // $dt = json_decode(json_encode(DB::select($sql)), true);

            $records_filtered = $qb->count();

            //ordering
            $order_column_id    = (int) $input['order'][0]['column'];
            $order_column_name  = $input['columns'][$order_column_id]['order_by'];
            $order_direction    = $input['order'][0]['dir'];

            if($order_column_name!=''){
                $qb = $qb->orderBy($order_column_name, $order_direction);
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);
            }
            else{
                $qb = $qb->limit($input['length']);
            }

            // $sql = Staff::bind_presql($qb->toSql(),$qb->getBindings());
            $response_dt = $qb->get();
            // $response_dt = json_decode(json_encode($response_dt), true);
            // $response_dt    = $this->append_relationships_objects($response_dt);
            // $response_dt    = $this->append_relationships_nulls($response_dt);
            $response       = Staff::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else {
            // $sql            = Staff::bind_presql($qb->toSql(),$qb->getBindings());
            // $response       = json_decode(json_encode(DB::select($sql)), true);
            $response = $qb->get();
            // if(!array_key_exists('lean', $input)){
            //     $response       = $this->append_relationships_objects($response);
            //     $response       = $this->append_relationships_nulls($response);
            // }
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
            $data[$key]['name']                     =   $staff->name;
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


    public function addSignature(){
        try{
            $input = Request::only('staff_id', 'file');
            $file = $input['file'];

            $staff = Staff::findOrFail($input['staff_id']);

            if($file!=0){
                $file_name = 'signature'.$staff->id.'.'.$file->getClientOriginalExtension();
                Storage::put('signatures/'.$file_name, file_get_contents($file));
                $staff->signature = $file_name;
                $staff->disableLogging();
                $staff->save();
            }

            return Response()->json(array('msg' => 'Success: signature saved'), 200);
        }
        catch (\Exception $e){
            return response()->json(['error'=>'something went wrong', 'msg'=>$e->getMessage()], 500);
        }
    }
}
