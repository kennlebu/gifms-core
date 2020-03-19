<?php

/**
 * Grants Information Management System
 * Finance management sysytem
 *
 * OpenAPI spec version: 1.0.0
 * Contact: mwangikevinn@gmail.com
 *
 * NOTE: This class is auto generated by the swagger code generator role.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use App\Models\StaffModels\Permission;
use JWTAuth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use App\Models\StaffModels\Role;
use Exception;
use App\Models\StaffModels\Staff;

class RolesApi extends Controller
{
    /**
     * Operation addRole
     *
     * Add a new role.
     *
     *
     * @return Http response
     */
    public function addRole()
    {
        $form = Request::all();
        $role = new Role;
        $role->name = $form['name'];
        $role->acronym = $form['acronym'];
        $role->display_name = $form['display_name'];

        if($role->save()) {
            return Response()->json(array('msg' => 'Success: role added','role' => $role), 200);
        }
    }
    




















    /**
     * Operation updateRole
     *
     * Update an existing role.
     *
     *
     * @return Http response
     */
    public function updateRole()
    {
        $form = Request::all();
        $role = Role::find($form['id']);
        $role->name = $form['name'];
        $role->acronym = $form['acronym'];
        $role->display_name = $form['display_name'];

        if($role->save()) {
            return Response()->json(array('msg' => 'Success: role updated','role' => $role), 200);
        }
    }
    




















    /**
     * Operation deleteRole
     *
     * Deletes an role.
     *
     * @param int $role_id role id to delete (required)
     *
     * @return Http response
     */
    public function deleteRole($role_id)
    {
        $deleted = Role::destroy($role_id);
        if($deleted){
            return response()->json(['msg'=>"role deleted"], 200,array(),JSON_PRETTY_PRINT);
        }else{
            return response()->json(['error'=>"Something went wrong"], 500,array(),JSON_PRETTY_PRINT);
        }
    }
    
    



















    /**

     * Operation getRoleById
     *
     * Find role by ID.
     *
     * @param int $role_id ID of role to return object (required)
     *
     * @return Http response
     */
    public function getRoleById($role_id)
    {
        try{
            $response   = Role::with("permissions")->findOrFail($role_id);           
            return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }

















    
    /**
     * Operation updateRolePermissions
     *
     * Update Role Permissions by ID.
     *
     * @param int $role_id ID of role to return object (required)
     *
     * @return Http response
     */
    public function updateRolePermissions($role_id)
    {
        $form = Request::only(
            'permissions'
            );

        try{
            $role  =   Role::findOrFail($role_id);
            $role->permissions()->sync($form->permissions);
            $response   = Role::with("permissions")->findOrFail($role_id);
           
            return response()->json(['msg'=>"Permissions Updated", 'role'=>$response], 200,array(),JSON_PRETTY_PRINT);

        }catch(Exception $e){
            $response =  ["error"=>"Something went wrong"];
            return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
        }
    }









    /**
 * Operation getRightsTransfers
 *
 * Get rights transfers.
 *
 * @return Http response
 */
public function getRightsTransfers()
{
    $input = Request::all();        

    try{
        $qb = DB::table('rights_transfers');

        if(array_key_exists('from_staff_id', $input)){
            $qb = $qb->where('from_staff_id', $input['from_staff_id']);
        }

        if(array_key_exists('to_staff_id', $input)){
            $qb = $qb->where('to_staff_id', $input['to_staff_id']);
        }

        if(array_key_exists('type', $input)){
            $qb = $qb->where('right_type', $input['type']);
        }

        $response = $qb->first();
       
        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);

    }catch(Exception $e){
        $response =  ["error"=>"Something went wrong", "msg"=>$e->getMessage()];
        return response()->json($response, 500,array(),JSON_PRETTY_PRINT);
    }
}
    




















    /**
     * Operation rolesGet
     *
     * roles List.
     *
     *
     * @return Http response
     */
    public function rolesGet()
    {
        $input = Request::all();
        //query builder
        $qb = DB::table('roles');

        $qb->whereNull('roles.deleted_at');

        $response;
        $response_dt;

        $total_records          = $qb->count();
        $records_filtered       = 0;

        //searching
        if(array_key_exists('searchval', $input)){
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('roles.id','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('roles.name','like', '\'%' . $input['searchval']. '%\'');
                $query->orWhere('roles.acronym','like', '\'%' . $input['searchval']. '%\'');
            });

            $sql = Staff::bind_presql($qb->toSql(),$qb->getBindings());
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

        //for a staff member
        if(array_key_exists('for_staff_id', $input)){
            $qb->select(DB::raw('roles.*'))
                ->leftJoin('user_roles', 'user_roles.role_id', '=', 'roles.id')
                ->leftJoin('staff', 'staff.id', '=', 'user_roles.user_id')
                ->where('staff.id', '=', "'".$input['for_staff_id']."'")
                ->groupBy('roles.id');
        }

        if(array_key_exists('datatables', $input)){
            //searching
            $qb->where(function ($query) use ($input) {                
                $query->orWhere('roles.id','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('roles.name','like', '\'%' . $input['search']['value']. '%\'');
                $query->orWhere('roles.acronym','like', '\'%' . $input['search']['value']. '%\'');
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

            //staff_id
            if(array_key_exists('staff_id', $input)){
                $qb->select(DB::raw('roles.*'))
                     ->rightJoin('user_roles', 'user_roles.role_id', '=', 'roles.id')
                     ->rightJoin('staff', 'staff.id', '=', 'user_roles.staff_id')
                     ->where('staff.id', '=', "'".$input['staff_id']."'")
                     ->groupBy('roles.id');
            }

            //limit $ offset
            if((int)$input['start']!= 0 ){
                $response_dt    =   $qb->limit($input['length'])->offset($input['start']);
            }else{
                $qb->limit($input['length']);
            }

            $sql = Staff::bind_presql($qb->toSql(),$qb->getBindings());

            $response_dt = DB::select($sql);
            $response_dt = json_decode(json_encode($response_dt), true);
            $response_dt    = $this->append_relationships_objects($response_dt);
            $response       = Staff::arr_to_dt_response( 
                $response_dt, $input['draw'],
                $total_records,
                $records_filtered
                );
        }
        else{
            $sql            = Staff::bind_presql($qb->toSql(),$qb->getBindings());
            $response       = json_decode(json_encode(DB::select($sql)), true);
            if(!array_key_exists('lean', $input)){
                $response       = $this->append_relationships_objects($response);
            }
        }

        return response()->json($response, 200,array(),JSON_PRETTY_PRINT);
    }




















    public function append_relationships_objects($data = array()){
        foreach ($data as $key => $value) {
            $roles = Role::find($data[$key]['id']);
            $data[$key]['permissions']                   = $roles->permissions;
        }

        return $data;
    }

    public function assignUserRoles(){
        try{
            $form = Request::all();
            $user_id = $form['user_id'];
            $new_roles = $form['roles'];
            $old_roles = array();

            $user_roles = DB::table('user_roles')->select('user_id', 'role_id')->where('user_id', $user_id)->get();
            foreach($user_roles as $user_role){
                array_push($old_roles, $user_role['role_id']);
            }

            // Remove removed roles
            foreach($old_roles as $old_role){
                if(!in_array($old_role, $new_roles)){
                    DB::table('user_roles')->where('user_id', $user_id)->where('role_id', $old_role)->delete();
                }
            }

            // Add new roles
            $insert_array = [];
            foreach($new_roles as $new_role){
                if(!in_array($new_role, $old_roles)){
                    $insert_array[] = ['user_id'=>$user_id, 'role_id'=>$new_role];
                }
            }
            if(!empty($insert_array))
                DB::table('user_roles')->insert($insert_array);

            return Response()->json(array('msg' => 'Success: roles updated'), 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'Something went wrong'], 500);
        }
    }

    public function assignRolePermissions(){
        try{
            $form = Request::all();
            $role_id = $form['role_id'];
            $new_perms = $form['permissions'];
            $old_perms = [];

            $old_perms = DB::table('role_permissions')->where('role_id', $role_id)->pluck('permission_id')->toArray();

            // Remove removed roles
            foreach($old_perms as $old_perm){
                if(!in_array($old_perm, $new_perms)){
                    DB::table('role_permissions')->where('role_id', $role_id)->where('permission_id', $old_perm)->delete();
                }
            }

            // Add new roles
            $insert_array = array();
            foreach($new_perms as $new_perm){
                if(!in_array($new_perm, $old_perms)){
                    $insert_array[] = ['role_id'=>$role_id, 'permission_id'=>$new_perm];
                }
            }
            if(!empty($insert_array))
                DB::table('role_permissions')->insert($insert_array);

            return Response()->json(array('msg' => 'Success: permissions updated'), 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'Something went wrong'], 500);
        }
    }





    /**
     * transferUserRoles
     * Transfer user roles to another user
     */
    public function transferUserRoles(){
        try{
            $input = Request::all();
            $user = JWTAuth::parseToken()->authenticate();
            
            // Roles being transfered to another user
            $outgoing_roles = $input['roles'];
            $outgoing_role_ids = array();
            foreach($outgoing_roles as $role){
                array_push($outgoing_role_ids, (int)$role);
            }

            $receiver_id = $input['to_user_id'];

            // Roles the receiver already has
            $receiver_roles = DB::table('user_roles')->select('role_id')->where('user_id', (int)$receiver_id)->get();
            $receiver_role_ids = array();
            foreach($receiver_roles as $role){
                array_push($receiver_role_ids, $role['role_id']);
            }

            $roles_inserts = array();
            $transfers_inserts = array();
            foreach($outgoing_role_ids as $to_role){
                array_push($roles_inserts, array('user_id'=>(int)$receiver_id, 'role_id'=>(int)$to_role));
            }

            // Transfer roles
            DB::table('user_roles')->insert($roles_inserts);
            DB::table('user_roles')->where('user_id', $user->id)
                                       ->whereIn('role_id', $outgoing_role_ids)
                                       ->delete();
            
            // Insert into right_transfers table
            $transfers_inserts = array('from_staff_id'=>(int)$user->id, 
                'to_staff_id'=>(int)$receiver_id, 
                'right_type'=>'role', 
                'rights'=>json_encode($outgoing_role_ids),
                'transfered_on'=>date("Y-m-d H:i:s")
            );
            DB::table('rights_transfers')->insert($transfers_inserts);


            return Response()->json(array('msg' => 'Success: roles transfered'), 200);
        }
        catch(\Exception $e){
            return response()->json(['error'=>'Something went wrong'], 500);
        }

    }
}
