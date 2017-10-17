<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'));

        //DATA
        
        // //refferences
        // $this->call(migrate_lookup_data::class);
        // $this->call(migrate_approvals_data::class);
        // $this->call(migrate_departments_data::class);
        // $this->call(migrate_projects_data::class);
        
        // $this->call(migrate_banking_data::class);
        // //personell
        // $this->call(migrate_staff_data::class);
        // $this->call(migrate_suppliers_data::class);

        // //accounting
        // $this->call(migrate_accounting_data::class);
        // $this->call(migrate_payment_data::class);
        // $this->call(migrate_lpo_data::class);
        // $this->call(migrate_invoice_data::class);
        // $this->call(migrate_mobile_payment_data::class);
        // $this->call(migrate_finance_data::class);
        // $this->call(migrate_advances_data::class);
        // $this->call(migrate_claims_data::class);



        // //KEYS

        // //refferences
        // $this->call(migrate_lookup_keys::class);
        // $this->call(migrate_approvals_keys::class);
        // $this->call(migrate_departments_keys::class);
        // $this->call(migrate_projects_keys::class);
        
        // $this->call(migrate_banking_keys::class);
        // //personell
        // $this->call(migrate_staff_keys::class);
        // $this->call(migrate_suppliers_keys::class);

        // //accounting
        // $this->call(migrate_accounting_keys::class);
        // $this->call(migrate_payment_keys::class); 
        // $this->call(migrate_lpo_keys::class);
        // $this->call(migrate_invoice_keys::class); 
        // $this->call(migrate_mobile_payment_keys::class);
        // $this->call(migrate_finance_keys::class);
        // $this->call(migrate_advances_keys::class);
        // $this->call(migrate_claims_keys::class);



        // //POST MIGRATION

        // // drop keys after migration
        // $this->call(post_keys_migration_migration::class);
        // $this->call(post_migration_allocations_data::class);
        // $this->call(post_migration_keys_drop::class);










        //LOGS

        //refferences
        // $this->call(migrate_lookup_logs::class);
        // $this->call(migrate_approvals_logs::class);
        // $this->call(migrate_departments_logs::class);
        // $this->call(migrate_projects_logs::class);
        
        // $this->call(migrate_banking_logs::class);
        // //personell
        // $this->call(migrate_staff_logs::class);
        // $this->call(migrate_suppliers_logs::class);

        // //accounting
        // $this->call(migrate_accounting_logs::class);
        // $this->call(migrate_payment_logs::class);
        // $this->call(migrate_lpo_logs::class);
        $this->call(migrate_invoice_logs::class);
        // $this->call(migrate_mobile_payment_logs::class);
        // $this->call(migrate_finance_logs::class);
        // $this->call(migrate_advances_logs::class);
        // $this->call(migrate_claims_keys::class);
        
    }
}
