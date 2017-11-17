<?php

use Illuminate\Database\Seeder;
use App\Models\StaffModels\Permission;
use App\Models\StaffModels\Role;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;

class migrate_staff_roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {












        $data = DB::table('permissions')->where('name', 'NOT LIKE', '%APPROVE%')->get();

        $data_to_migrate=array();

        $s_admin = Role::findOrFail(1);

        foreach ($data as $key => $value) {

            $permision = Permission::findOrFail($value['id']);
            $s_admin->attachPermission($permision);

            echo "\n Permissions-$key---";
            echo $data[$key]['display_name'];
        }
        
        echo "\n migrate super admin permissions------------------------------------------------------------------------------\n";




























        $data = DB::table('permissions')->where('name', 'NOT LIKE', '%APPROVE%')->get();

        $data_to_migrate=array();

        $admin = Role::findOrFail(2);

        foreach ($data as $key => $value) {

            $permision = Permission::findOrFail($value['id']);
            $admin->attachPermission($permision);

            echo "\n Permissions-$key---";
            echo $data[$key]['display_name'];
        }
        
        echo "\n migrate admin permissions------------------------------------------------------------------------------\n";


























        // $perms  =   array(
        //             'READ_LPO_-1',
        //             'READ_LPO_-2',
        //             'READ_LPO_1',
        //             'READ_LPO_2',
        //             'READ_LPO_3',
        //             'READ_LPO_4',
        //             'READ_LPO_5',
        //             'READ_LPO_6',
        //             'READ_LPO_7',
        //             'READ_LPO_8',
        //             'READ_LPO_9',
        //             'READ_LPO_10',
        //             'READ_LPO_11',
        //             'READ_LPO_12',
        //             'READ_LPO_13',
        //             'READ_LPO_14',
        //             'CREATE_LPO',
        //             'CREATE_LPO_OBO',
        //             'REQUEST_LPO',
        //             'REQUEST_LPO_OBO',
        //             'UPDATE_LPO_-1',
        //             'UPDATE_LPO_-2',
        //             'UPDATE_LPO_1',
        //             'UPDATE_LPO_2',
        //             'UPDATE_LPO_3',
        //             'UPDATE_LPO_4',
        //             'UPDATE_LPO_5',
        //             'UPDATE_LPO_6',
        //             'UPDATE_LPO_7',
        //             'UPDATE_LPO_8',
        //             'UPDATE_LPO_9',
        //             'UPDATE_LPO_10',
        //             'UPDATE_LPO_11',
        //             'UPDATE_LPO_12',
        //             'UPDATE_LPO_13',
        //             'UPDATE_LPO_14',
        //             'APPROVE_LPO_3',
        //             'APPROVE_LPO_13',
        //             'APPROVE_LPO_4',
        //             'APPROVE_LPO_5',
        //             'DELETE_LPO_1',
        //             'DELETE_LPO_2',
        //             'DELETE_LPO_3',
        //             'DELETE_LPO_4',
        //             'DELETE_LPO_5',
        //             'DELETE_LPO_6',
        //             'DELETE_LPO_7',
        //             'DELETE_LPO_8',
        //             'DELETE_LPO_9',
        //             'DELETE_LPO_10',
        //             'DELETE_LPO_11',
        //             'DELETE_LPO_12',
        //             'DELETE_LPO_13',
        //             'DELETE_LPO_14',
        //             'READ_INVOICE_-1',
        //             'READ_INVOICE_-2',
        //             'READ_INVOICE_1',
        //             'READ_INVOICE_2',
        //             'READ_INVOICE_3',
        //             'READ_INVOICE_4',
        //             'READ_INVOICE_5',
        //             'READ_INVOICE_6',
        //             'READ_INVOICE_7',
        //             'READ_INVOICE_8',
        //             'READ_INVOICE_9',
        //             'READ_INVOICE_10',
        //             'READ_INVOICE_11',
        //             'READ_INVOICE_12',
        //             'CREATE_INVOICE',
        //             'CREATE_INVOICE_OBO',
        //             'REQUEST_INVOICE',
        //             'REQUEST_INVOICE_OBO',
        //             'UPDATE_INVOICE_-1',
        //             'UPDATE_INVOICE_-2',
        //             'UPDATE_INVOICE_1',
        //             'UPDATE_INVOICE_2',
        //             'UPDATE_INVOICE_3',
        //             'UPDATE_INVOICE_4',
        //             'UPDATE_INVOICE_5',
        //             'UPDATE_INVOICE_6',
        //             'UPDATE_INVOICE_7',
        //             'UPDATE_INVOICE_8',
        //             'UPDATE_INVOICE_9',
        //             'UPDATE_INVOICE_10',
        //             'UPDATE_INVOICE_11',
        //             'UPDATE_INVOICE_12',
        //             'APPROVE_INVOICE_1',
        //             'APPROVE_INVOICE_12',
        //             'APPROVE_INVOICE_2',
        //             'APPROVE_INVOICE_3',
        //             'DELETE_INVOICE_1',
        //             'DELETE_INVOICE_2',
        //             'DELETE_INVOICE_3',
        //             'DELETE_INVOICE_4',
        //             'DELETE_INVOICE_5',
        //             'DELETE_INVOICE_6',
        //             'DELETE_INVOICE_7',
        //             'DELETE_INVOICE_8',
        //             'DELETE_INVOICE_9',
        //             'DELETE_INVOICE_10',
        //             'DELETE_INVOICE_11',
        //             'DELETE_INVOICE_12',
        //             'READ_CLAIM_-1',
        //             'READ_CLAIM_-2',
        //             'READ_CLAIM_1',
        //             'READ_CLAIM_2',
        //             'READ_CLAIM_3',
        //             'READ_CLAIM_4',
        //             'READ_CLAIM_5',
        //             'READ_CLAIM_6',
        //             'READ_CLAIM_7',
        //             'READ_CLAIM_8',
        //             'READ_CLAIM_9',
        //             'READ_CLAIM_10',
        //             'CREATE_CLAIM',
        //             'CREATE_CLAIM_OBO',
        //             'REQUEST_CLAIM',
        //             'REQUEST_CLAIM_OBO',
        //             'UPDATE_CLAIM_-1',
        //             'UPDATE_CLAIM_-2',
        //             'UPDATE_CLAIM_1',
        //             'UPDATE_CLAIM_2',
        //             'UPDATE_CLAIM_3',
        //             'UPDATE_CLAIM_4',
        //             'UPDATE_CLAIM_5',
        //             'UPDATE_CLAIM_6',
        //             'UPDATE_CLAIM_7',
        //             'UPDATE_CLAIM_8',
        //             'UPDATE_CLAIM_9',
        //             'UPDATE_CLAIM_10',
        //             'APPROVE_CLAIM_3',
        //             'APPROVE_CLAIM_13',
        //             'APPROVE_CLAIM_4',
        //             'APPROVE_CLAIM_5',
        //             'DELETE_CLAIM_1',
        //             'DELETE_CLAIM_2',
        //             'DELETE_CLAIM_3',
        //             'DELETE_CLAIM_4',
        //             'DELETE_CLAIM_5',
        //             'DELETE_CLAIM_6',
        //             'DELETE_CLAIM_7',
        //             'DELETE_CLAIM_8',
        //             'DELETE_CLAIM_9',
        //             'DELETE_CLAIM_10',
        //             'READ_MOBILE_PAYMENT_-1',
        //             'READ_MOBILE_PAYMENT_-2',
        //             'READ_MOBILE_PAYMENT_1',
        //             'READ_MOBILE_PAYMENT_2',
        //             'READ_MOBILE_PAYMENT_3',
        //             'READ_MOBILE_PAYMENT_4',
        //             'READ_MOBILE_PAYMENT_5',
        //             'READ_MOBILE_PAYMENT_6',
        //             'READ_MOBILE_PAYMENT_7',
        //             'READ_MOBILE_PAYMENT_8',
        //             'READ_MOBILE_PAYMENT_9',
        //             'READ_MOBILE_PAYMENT_10',
        //             'READ_MOBILE_PAYMENT_11',
        //             'READ_MOBILE_PAYMENT_12',
        //             'READ_MOBILE_PAYMENT_13',
        //             'CREATE_MOBILE_PAYMENT',
        //             'CREATE_MOBILE_PAYMENT_OBO',
        //             'REQUEST_MOBILE_PAYMENT',
        //             'REQUEST_MOBILE_PAYMENT_OBO',
        //             'UPDATE_MOBILE_PAYMENT_-1',
        //             'UPDATE_MOBILE_PAYMENT_-2',
        //             'UPDATE_MOBILE_PAYMENT_1',
        //             'UPDATE_MOBILE_PAYMENT_2',
        //             'UPDATE_MOBILE_PAYMENT_3',
        //             'UPDATE_MOBILE_PAYMENT_4',
        //             'UPDATE_MOBILE_PAYMENT_5',
        //             'UPDATE_MOBILE_PAYMENT_6',
        //             'UPDATE_MOBILE_PAYMENT_7',
        //             'UPDATE_MOBILE_PAYMENT_8',
        //             'UPDATE_MOBILE_PAYMENT_9',
        //             'UPDATE_MOBILE_PAYMENT_10',
        //             'UPDATE_MOBILE_PAYMENT_11',
        //             'UPDATE_MOBILE_PAYMENT_12',
        //             'UPDATE_MOBILE_PAYMENT_13',
        //             'APPROVE_MOBILE_PAYMENT_3',
        //             'APPROVE_MOBILE_PAYMENT_13',
        //             'APPROVE_MOBILE_PAYMENT_4',
        //             'APPROVE_MOBILE_PAYMENT_5',
        //             'DELETE_MOBILE_PAYMENT_1',
        //             'DELETE_MOBILE_PAYMENT_2',
        //             'DELETE_MOBILE_PAYMENT_3',
        //             'DELETE_MOBILE_PAYMENT_4',
        //             'DELETE_MOBILE_PAYMENT_5',
        //             'DELETE_MOBILE_PAYMENT_6',
        //             'DELETE_MOBILE_PAYMENT_7',
        //             'DELETE_MOBILE_PAYMENT_8',
        //             'DELETE_MOBILE_PAYMENT_9',
        //             'DELETE_MOBILE_PAYMENT_10',
        //             'DELETE_MOBILE_PAYMENT_11',
        //             'DELETE_MOBILE_PAYMENT_12',
        //             'DELETE_MOBILE_PAYMENT_13',
        //             'READ_ADVANCE_-1',
        //             'READ_ADVANCE_-2',
        //             'READ_ADVANCE_1',
        //             'READ_ADVANCE_2',
        //             'READ_ADVANCE_3',
        //             'READ_ADVANCE_4',
        //             'READ_ADVANCE_5',
        //             'READ_ADVANCE_6',
        //             'READ_ADVANCE_7',
        //             'READ_ADVANCE_8',
        //             'READ_ADVANCE_9',
        //             'READ_ADVANCE_10',
        //             'READ_ADVANCE_11',
        //             'READ_ADVANCE_12',
        //             'READ_ADVANCE_13',
        //             'CREATE_ADVANCE',
        //             'CREATE_ADVANCE_OBO',
        //             'REQUEST_ADVANCE',
        //             'REQUEST_ADVANCE_OBO',
        //             'UPDATE_ADVANCE_-1',
        //             'UPDATE_ADVANCE_-2',
        //             'UPDATE_ADVANCE_1',
        //             'UPDATE_ADVANCE_2',
        //             'UPDATE_ADVANCE_3',
        //             'UPDATE_ADVANCE_4',
        //             'UPDATE_ADVANCE_5',
        //             'UPDATE_ADVANCE_6',
        //             'UPDATE_ADVANCE_7',
        //             'UPDATE_ADVANCE_8',
        //             'UPDATE_ADVANCE_9',
        //             'UPDATE_ADVANCE_10',
        //             'UPDATE_ADVANCE_11',
        //             'UPDATE_ADVANCE_12',
        //             'UPDATE_ADVANCE_13',
        //             'APPROVE_ADVANCE_3',
        //             'APPROVE_ADVANCE_13',
        //             'APPROVE_ADVANCE_4',
        //             'APPROVE_ADVANCE_5',
        //             'DELETE_ADVANCE_1',
        //             'DELETE_ADVANCE_2',
        //             'DELETE_ADVANCE_3',
        //             'DELETE_ADVANCE_4',
        //             'DELETE_ADVANCE_5',
        //             'DELETE_ADVANCE_6',
        //             'DELETE_ADVANCE_7',
        //             'DELETE_ADVANCE_8',
        //             'DELETE_ADVANCE_9',
        //             'DELETE_ADVANCE_10',
        //             'DELETE_ADVANCE_11',
        //             'DELETE_ADVANCE_12',
        //             'DELETE_ADVANCE_13',
        //             'READ_LOOKUP_DATA',
        //             'CREATE_LOOKUP_DATA',
        //             'UPDATE_LOOKUP_DATA',
        //             'DELETE_LOOKUP_DATA',
        //             'CREATE_SUPPLIERS_DATA',
        //             'UPDATE_SUPPLIERS_DATA',
        //             'DELETE_SUPPLIERS_DATA'

        //             );













        $perms  =   array(
                    
                    'APPROVE_LPO_5',

                    'APPROVE_MOBILE_PAYMENT_8',

                    'APPROVE_INVOICE_3',

                    'APPROVE_CLAIM_4',

                    'APPROVE_ADVANCE_4',

                    
                    'READ_LPO_-2',
                    'READ_MOBILE_PAYMENT_-2',
                    'READ_INVOICE_-2',
                    'READ_CLAIM_-2',
                    'READ_ADVANCE_-2'

                    );


        $user = Role::findOrFail(3);

        foreach ($perms as $key => $value) {

            $data = DB::table('permissions')
                    ->where('name', $value)
                    ->first();

            $permision = Permission::findOrFail($data['id']);
            try {
                $user->attachPermission($permision);
                
            } catch (Exception $e) {
                
            }

            echo "\n Permissions-$value---";
            // echo $data[$key]['display_name'];
        }
        
        echo "\n migrate director permissions------------------------------------------------------------------------------\n";


















        $perms  =   array(
                    
                    'APPROVE_LPO_4',

                    'APPROVE_MOBILE_PAYMENT_3',

                    'APPROVE_INVOICE_2',

                    'APPROVE_CLAIM_3',

                    'APPROVE_ADVANCE_3',

                    
                    'READ_LPO_-2',
                    'READ_MOBILE_PAYMENT_-2',
                    'READ_INVOICE_-2',
                    'READ_CLAIM_-2',
                    'READ_ADVANCE_-2'
                    );


        $user = Role::findOrFail(5);

        foreach ($perms as $key => $value) {

            $data = DB::table('permissions')
                    ->where('name', $value)
                    ->first();

            $permision = Permission::findOrFail($data['id']);
            try {
                $user->attachPermission($permision);
                
            } catch (Exception $e) {
                
            }

            echo "\n Permissions-$value---";
            // echo $data[$key]['display_name'];
        }
        
        echo "\n migrate financial-controller permissions------------------------------------------------------------------------------\n";


















        $perms  =   array(
                    

                    'APPROVE_LPO_3',

                    'APPROVE_MOBILE_PAYMENT_2',

                    'APPROVE_INVOICE_1',

                    'APPROVE_CLAIM_2',

                    'APPROVE_ADVANCE_2'

                    );


        $user = Role::findOrFail(6);

        foreach ($perms as $key => $value) {

            $data = DB::table('permissions')
                    ->where('name', $value)
                    ->first();

            $permision = Permission::findOrFail($data['id']);
            try {
                $user->attachPermission($permision);
                
            } catch (Exception $e) {
                
            }

            echo "\n Permissions-$value---";
            // echo $data[$key]['display_name'];
        }
        
        echo "\n migrate program-manager permissions------------------------------------------------------------------------------\n";


















        $perms  =   array(
                    'READ_LPO_-1',
                    // 'READ_LPO_-2',
                    'READ_LPO_1',
                    'READ_LPO_2',
                    'READ_LPO_3',
                    'READ_LPO_4',
                    'READ_LPO_5',
                    'READ_LPO_6',
                    'READ_LPO_7',
                    'READ_LPO_8',
                    'READ_LPO_9',
                    'READ_LPO_10',
                    'READ_LPO_11',
                    'READ_LPO_12',
                    'READ_LPO_13',
                    // 'READ_LPO_14',
                    'CREATE_LPO',
                    // 'CREATE_LPO_OBO',
                    'REQUEST_LPO',
                    // 'REQUEST_LPO_OBO',
                    // 'UPDATE_LPO_-1',
                    // 'UPDATE_LPO_-2',
                    'UPDATE_LPO_1',
                    'UPDATE_LPO_2',
                    // 'UPDATE_LPO_3',
                    // 'UPDATE_LPO_4',
                    // 'UPDATE_LPO_5',
                    // 'UPDATE_LPO_6',
                    // 'UPDATE_LPO_7',
                    // 'UPDATE_LPO_8',
                    // 'UPDATE_LPO_9',
                    // 'UPDATE_LPO_10',
                    // 'UPDATE_LPO_11',
                    // 'UPDATE_LPO_12',
                    // 'UPDATE_LPO_13',
                    // 'UPDATE_LPO_14',
                    // 'APPROVE_LPO_3',
                    // 'APPROVE_LPO_13',
                    // 'APPROVE_LPO_4',
                    // 'APPROVE_LPO_5',
                    'DELETE_LPO_1',
                    'DELETE_LPO_2',
                    // 'DELETE_LPO_3',
                    // 'DELETE_LPO_4',
                    // 'DELETE_LPO_5',
                    // 'DELETE_LPO_6',
                    // 'DELETE_LPO_7',
                    // 'DELETE_LPO_8',
                    // 'DELETE_LPO_9',
                    // 'DELETE_LPO_10',
                    // 'DELETE_LPO_11',
                    // 'DELETE_LPO_12',
                    // 'DELETE_LPO_13',
                    // 'DELETE_LPO_14',
                    'READ_INVOICE_-1',
                    // 'READ_INVOICE_-2',
                    'READ_INVOICE_1',
                    'READ_INVOICE_2',
                    'READ_INVOICE_3',
                    'READ_INVOICE_4',
                    'READ_INVOICE_5',
                    'READ_INVOICE_6',
                    'READ_INVOICE_7',
                    'READ_INVOICE_8',
                    'READ_INVOICE_9',
                    'READ_INVOICE_10',
                    'READ_INVOICE_11',
                    'READ_INVOICE_12',
                    'CREATE_INVOICE',
                    // 'CREATE_INVOICE_OBO',
                    'REQUEST_INVOICE',
                    // 'REQUEST_INVOICE_OBO',
                    // 'UPDATE_INVOICE_-1',
                    // 'UPDATE_INVOICE_-2',
                    // 'UPDATE_INVOICE_1',
                    // 'UPDATE_INVOICE_2',
                    // 'UPDATE_INVOICE_3',
                    // 'UPDATE_INVOICE_4',
                    // 'UPDATE_INVOICE_5',
                    // 'UPDATE_INVOICE_6',
                    // 'UPDATE_INVOICE_7',
                    // 'UPDATE_INVOICE_8',
                    // 'UPDATE_INVOICE_9',
                    'UPDATE_INVOICE_10',
                    'UPDATE_INVOICE_11',
                    // 'UPDATE_INVOICE_12',
                    // 'APPROVE_INVOICE_1',
                    // 'APPROVE_INVOICE_12',
                    // 'APPROVE_INVOICE_2',
                    // 'APPROVE_INVOICE_3',
                    // 'DELETE_INVOICE_1',
                    // 'DELETE_INVOICE_2',
                    // 'DELETE_INVOICE_3',
                    // 'DELETE_INVOICE_4',
                    // 'DELETE_INVOICE_5',
                    // 'DELETE_INVOICE_6',
                    // 'DELETE_INVOICE_7',
                    // 'DELETE_INVOICE_8',
                    // 'DELETE_INVOICE_9',
                    'DELETE_INVOICE_10',
                    'DELETE_INVOICE_11',
                    // 'DELETE_INVOICE_12',
                    'READ_CLAIM_-1',
                    // 'READ_CLAIM_-2',
                    'READ_CLAIM_1',
                    'READ_CLAIM_2',
                    'READ_CLAIM_3',
                    'READ_CLAIM_4',
                    'READ_CLAIM_5',
                    'READ_CLAIM_6',
                    'READ_CLAIM_7',
                    'READ_CLAIM_8',
                    'READ_CLAIM_9',
                    'READ_CLAIM_10',
                    'CREATE_CLAIM',
                    // 'CREATE_CLAIM_OBO',
                    'REQUEST_CLAIM',
                    // 'REQUEST_CLAIM_OBO',
                    // 'UPDATE_CLAIM_-1',
                    // 'UPDATE_CLAIM_-2',
                    'UPDATE_CLAIM_1',
                    // 'UPDATE_CLAIM_2',
                    // 'UPDATE_CLAIM_3',
                    // 'UPDATE_CLAIM_4',
                    // 'UPDATE_CLAIM_5',
                    // 'UPDATE_CLAIM_6',
                    // 'UPDATE_CLAIM_7',
                    // 'UPDATE_CLAIM_8',
                    // 'UPDATE_CLAIM_9',
                    // 'UPDATE_CLAIM_10',
                    // 'APPROVE_CLAIM_3',
                    // 'APPROVE_CLAIM_13',
                    // 'APPROVE_CLAIM_4',
                    // 'APPROVE_CLAIM_5',
                    'DELETE_CLAIM_1',
                    // 'DELETE_CLAIM_2',
                    // 'DELETE_CLAIM_3',
                    // 'DELETE_CLAIM_4',
                    // 'DELETE_CLAIM_5',
                    // 'DELETE_CLAIM_6',
                    // 'DELETE_CLAIM_7',
                    // 'DELETE_CLAIM_8',
                    // 'DELETE_CLAIM_9',
                    // 'DELETE_CLAIM_10',
                    'READ_MOBILE_PAYMENT_-1',
                    // 'READ_MOBILE_PAYMENT_-2',
                    'READ_MOBILE_PAYMENT_1',
                    'READ_MOBILE_PAYMENT_2',
                    'READ_MOBILE_PAYMENT_3',
                    'READ_MOBILE_PAYMENT_4',
                    'READ_MOBILE_PAYMENT_5',
                    'READ_MOBILE_PAYMENT_6',
                    'READ_MOBILE_PAYMENT_7',
                    'READ_MOBILE_PAYMENT_8',
                    'READ_MOBILE_PAYMENT_9',
                    'READ_MOBILE_PAYMENT_10',
                    'READ_MOBILE_PAYMENT_11',
                    'READ_MOBILE_PAYMENT_12',
                    'READ_MOBILE_PAYMENT_13',
                    'CREATE_MOBILE_PAYMENT',
                    //  'CREATE_MOBILE_PAYMENT_OBO',
                    'REQUEST_MOBILE_PAYMENT',
                    // 'REQUEST_MOBILE_PAYMENT_OBO',
                    // 'UPDATE_MOBILE_PAYMENT_-1',
                    // 'UPDATE_MOBILE_PAYMENT_-2',
                    'UPDATE_MOBILE_PAYMENT_1',
                    // 'UPDATE_MOBILE_PAYMENT_2',
                    // 'UPDATE_MOBILE_PAYMENT_3',
                    // 'UPDATE_MOBILE_PAYMENT_4',
                    // 'UPDATE_MOBILE_PAYMENT_5',
                    // 'UPDATE_MOBILE_PAYMENT_6',
                    // 'UPDATE_MOBILE_PAYMENT_7',
                    // 'UPDATE_MOBILE_PAYMENT_8',
                    // 'UPDATE_MOBILE_PAYMENT_9',
                    // 'UPDATE_MOBILE_PAYMENT_10',
                    // 'UPDATE_MOBILE_PAYMENT_11',
                    // 'UPDATE_MOBILE_PAYMENT_12',
                    // 'UPDATE_MOBILE_PAYMENT_13',
                    // 'APPROVE_MOBILE_PAYMENT_3',
                    // 'APPROVE_MOBILE_PAYMENT_13',
                    // 'APPROVE_MOBILE_PAYMENT_4',
                    // 'APPROVE_MOBILE_PAYMENT_5',
                    'DELETE_MOBILE_PAYMENT_1',
                    // 'DELETE_MOBILE_PAYMENT_2',
                    // 'DELETE_MOBILE_PAYMENT_3',
                    // 'DELETE_MOBILE_PAYMENT_4',
                    // 'DELETE_MOBILE_PAYMENT_5',
                    // 'DELETE_MOBILE_PAYMENT_6',
                    // 'DELETE_MOBILE_PAYMENT_7',
                    // 'DELETE_MOBILE_PAYMENT_8',
                    // 'DELETE_MOBILE_PAYMENT_9',
                    // 'DELETE_MOBILE_PAYMENT_10',
                    // 'DELETE_MOBILE_PAYMENT_11',
                    // 'DELETE_MOBILE_PAYMENT_12',
                    // 'DELETE_MOBILE_PAYMENT_13',
                    'READ_ADVANCE_-1',
                    // 'READ_ADVANCE_-2',
                    'READ_ADVANCE_1',
                    'READ_ADVANCE_2',
                    'READ_ADVANCE_3',
                    'READ_ADVANCE_4',
                    'READ_ADVANCE_5',
                    'READ_ADVANCE_6',
                    'READ_ADVANCE_7',
                    'READ_ADVANCE_8',
                    'READ_ADVANCE_9',
                    'READ_ADVANCE_10',
                    'READ_ADVANCE_11',
                    'READ_ADVANCE_12',
                    'READ_ADVANCE_13',
                    'CREATE_ADVANCE',
                    // 'CREATE_ADVANCE_OBO',
                    'REQUEST_ADVANCE',
                    // 'REQUEST_ADVANCE_OBO',
                    // 'UPDATE_ADVANCE_-1',
                    // 'UPDATE_ADVANCE_-2',
                    'UPDATE_ADVANCE_1',
                    // 'UPDATE_ADVANCE_2',
                    // 'UPDATE_ADVANCE_3',
                    // 'UPDATE_ADVANCE_4',
                    // 'UPDATE_ADVANCE_5',
                    // 'UPDATE_ADVANCE_6',
                    // 'UPDATE_ADVANCE_7',
                    // 'UPDATE_ADVANCE_8',
                    // 'UPDATE_ADVANCE_9',
                    // 'UPDATE_ADVANCE_10',
                    // 'UPDATE_ADVANCE_11',
                    // 'UPDATE_ADVANCE_12',
                    // 'UPDATE_ADVANCE_13',
                    // 'APPROVE_ADVANCE_3',
                    // 'APPROVE_ADVANCE_13',
                    // 'APPROVE_ADVANCE_4',
                    // 'APPROVE_ADVANCE_5',
                    'DELETE_ADVANCE_1',
                    // 'DELETE_ADVANCE_2',
                    // 'DELETE_ADVANCE_3',
                    // 'DELETE_ADVANCE_4',
                    // 'DELETE_ADVANCE_5',
                    // 'DELETE_ADVANCE_6',
                    // 'DELETE_ADVANCE_7',
                    // 'DELETE_ADVANCE_8',
                    // 'DELETE_ADVANCE_9',
                    // 'DELETE_ADVANCE_10',
                    // 'DELETE_ADVANCE_11',
                    // 'DELETE_ADVANCE_12',
                    // 'DELETE_ADVANCE_13',
                    // 'READ_LOOKUP_DATA',
                    // 'CREATE_LOOKUP_DATA',
                    // 'UPDATE_LOOKUP_DATA',
                    // 'DELETE_LOOKUP_DATA',
                    'CREATE_SUPPLIERS_DATA',
                    'UPDATE_SUPPLIERS_DATA',
                    'DELETE_SUPPLIERS_DATA'

                    );


        $user = Role::findOrFail(7);

        foreach ($perms as $key => $value) {

            $data = DB::table('permissions')
                    ->where('name', $value)
                    ->first();

            $permision = Permission::findOrFail($data['id']);
            try {
                $user->attachPermission($permision);
                
            } catch (Exception $e) {
                
            }

            echo "\n Permissions-$value---";
            // echo $data[$key]['display_name'];
        }
        
        echo "\n migrate program-analyst permissions------------------------------------------------------------------------------\n";


















        $perms  =   array(

                    'CREATE_LPO_OBO',
                    'REQUEST_LPO_OBO',
                    'APPROVE_LPO_13',

                    'CREATE_MOBILE_PAYMENT_OBO',
                    'REQUEST_MOBILE_PAYMENT_OBO',
                    'APPROVE_MOBILE_PAYMENT_9',

                    'CREATE_INVOICE_OBO',
                    'REQUEST_INVOICE_OBO',
                    'APPROVE_INVOICE_12',

                    'CREATE_CLAIM_OBO',
                    'REQUEST_CLAIM_OBO',
                    'APPROVE_CLAIM_10',

                    'CREATE_ADVANCE_OBO',
                    'REQUEST_ADVANCE_OBO',
                    'APPROVE_ADVANCE_13',

                    
                    'READ_LPO_-2',
                    'READ_MOBILE_PAYMENT_-2',
                    'READ_INVOICE_-2',
                    'READ_CLAIM_-2',
                    'READ_ADVANCE_-2'
                    


                    );


        $user = Role::findOrFail(8);

        foreach ($perms as $key => $value) {

            $data = DB::table('permissions')
                    ->where('name', $value)
                    ->first();

            $permision = Permission::findOrFail($data['id']);
            try {
                $user->attachPermission($permision);
                
            } catch (Exception $e) {
                
            }

            echo "\n Permissions-$value---";
            // echo $data[$key]['display_name'];
        }
        
        echo "\n migrate accountant permissions------------------------------------------------------------------------------\n";




















        $perms  =   array(

                    'CREATE_LPO_OBO',
                    'REQUEST_LPO_OBO',
                    'APPROVE_LPO_13',

                    'CREATE_MOBILE_PAYMENT_OBO',
                    'REQUEST_MOBILE_PAYMENT_OBO',
                    'APPROVE_MOBILE_PAYMENT_9',

                    'CREATE_INVOICE_OBO',
                    'REQUEST_INVOICE_OBO',
                    'APPROVE_INVOICE_12',

                    'CREATE_CLAIM_OBO',
                    'REQUEST_CLAIM_OBO',
                    'APPROVE_CLAIM_10',

                    'CREATE_ADVANCE_OBO',
                    'REQUEST_ADVANCE_OBO',
                    'APPROVE_ADVANCE_13',

                    
                    'READ_LPO_-2',
                    'READ_MOBILE_PAYMENT_-2',
                    'READ_INVOICE_-2',
                    'READ_CLAIM_-2',
                    'READ_ADVANCE_-2'

                    );


        $user = Role::findOrFail(9);

        foreach ($perms as $key => $value) {

            $data = DB::table('permissions')
                    ->where('name', $value)
                    ->first();

            $permision = Permission::findOrFail($data['id']);
            try {
                $user->attachPermission($permision);
                
            } catch (Exception $e) {
                
            }

            echo "\n Permissions-$value---";
            // echo $data[$key]['display_name'];
        }
        
        echo "\n migrate assistant-accountant permissions------------------------------------------------------------------------------\n";


















        $perms  =   array(
                    

                    'CREATE_LPO_OBO',
                    'REQUEST_LPO_OBO',

                    'CREATE_MOBILE_PAYMENT_OBO',
                    'REQUEST_MOBILE_PAYMENT_OBO',

                    'CREATE_INVOICE_OBO',
                    'REQUEST_INVOICE_OBO',

                    'CREATE_CLAIM_OBO',
                    'REQUEST_CLAIM_OBO',

                    'CREATE_ADVANCE_OBO',
                    'REQUEST_ADVANCE_OBO',

                    
                    'READ_LPO_-2',
                    'READ_MOBILE_PAYMENT_-2',
                    'READ_INVOICE_-2',
                    'READ_CLAIM_-2',
                    'READ_ADVANCE_-2'

                    );


        $user = Role::findOrFail(10);

        foreach ($perms as $key => $value) {

            $data = DB::table('permissions')
                    ->where('name', $value)
                    ->first();

            $permision = Permission::findOrFail($data['id']);
            try {
                $user->attachPermission($permision);
                
            } catch (Exception $e) {
                
            }

            echo "\n Permissions-$value---";
            // echo $data[$key]['display_name'];
        }
        
        echo "\n migrate admin-manager permissions------------------------------------------------------------------------------\n";
       
















    	
        //
        $users = User::all();

        foreach ($users as $key => $user) {
        	try {
	        	//admin
	        	if ($user->security_group_id == 1) {
	        		$user->attachRole(1);
	        	}

	        	//fm
	        	if ($user->security_group_id == 2) {
	        		$user->attachRole(6);
	        		$user->attachRole(7);
	        	}

	        	//dir
	        	if ($user->security_group_id == 7) {
	        		$user->attachRole(3);
	        		$user->attachRole(7);
	        	}

	        	//admin-man
	        	if ($user->security_group_id == 8) {
	        		$user->attachRole(10);
	        		$user->attachRole(7);
	        	}

	        	//fc
	        	if ($user->security_group_id == 9) {
	        		$user->attachRole(5);
	        		$user->attachRole(7);
	        	}

	        	//analyst
	        	if ($user->security_group_id == 10) {
	        		$user->attachRole(7);
	        	}

	        	//acc
	        	if ($user->security_group_id == 11) {
	        		$user->attachRole(8);
	        		$user->attachRole(7);
	        	}

	        	//audit
	        	if ($user->security_group_id == 13) {
	        		$user->attachRole(11);
	        	}

	        	//asst-acc
	        	if ($user->security_group_id == 14) {
	        		$user->attachRole(9);
	        		$user->attachRole(7);
	        	}

	        	//audit
	        	if ($user->security_group_id == 15) {
	        		$user->attachRole(11);
	        	}
	        	//jack admin
	        	if ($user->email == 'jhungu@clintonhealthaccess.org') {
	        		$user->attachRole(1);
	        	}
	        	//davis pm admin
                if ($user->email == 'dkarambi@clintonhealthaccess.org') {
                    $user->attachRole(6);
                }
                if ($user->email == 'jayuma@clintonhealthaccess.org') {
                    $user->attachRole(6);
                }
        		
        	} catch (Exception $e) {
        		
        	}

            echo "\n Staff-$key---";
            echo $user->email;
            
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n\n";
    }
}
