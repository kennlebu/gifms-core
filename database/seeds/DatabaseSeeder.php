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


        DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->setFetchMode(PDO::FETCH_ASSOC);

        
        //refferences
        $this->call(migrate_lookup_data::class);
        $this->call(migrate_approvals_data::class);
        $this->call(migrate_departments_data::class);
        $this->call(migrate_projects_data::class);
        
        //personell
        $this->call(migrate_staff_data::class);
        $this->call(migrate_suppliers_data::class);

        //accounting
        $this->call(migrate_banking_data::class);
        $this->call(migrate_accounting_data::class);
        $this->call(migrate_payment_data::class);
        $this->call(migrate_lpo_data::class);
        $this->call(migrate_invoice_data::class);
        $this->call(migrate_mpesa_data::class);





        //refferences
        $this->call(migrate_lookup_keys::class);
        $this->call(migrate_approvals_keys::class);
        $this->call(migrate_departments_keys::class);
        $this->call(migrate_projects_keys::class);
        
        //personell
        $this->call(migrate_staff_keys::class);
        $this->call(migrate_suppliers_keys::class);

        //accounting
        $this->call(migrate_banking_keys::class);
        $this->call(migrate_accounting_keys::class);
        $this->call(migrate_payment_keys::class);
        $this->call(migrate_lpo_keys::class);
        $this->call(migrate_invoice_keys::class);   //waiting for definitions claims, staff_advances, mpesa_payments
        $this->call(migrate_mpesa_keys::class);





        // drop keys after migration
        $this->call(post_migration_keys_drop::class);
        
    }
}
