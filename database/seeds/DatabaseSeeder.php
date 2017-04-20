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
        //refferences
        $this->call(migrate_lookup_data::class);
        $this->call(migrate_approvals_data::class);
        $this->call(migrate_departments_data::class);
        $this->call(migrate_projects_data::class);
        
        //personell
        $this->call(migrate_employees_data::class);
        $this->call(migrate_suppliers_data::class);

        //accounting
        $this->call(migrate_banking_data::class);
        $this->call(migrate_accounting_data::class);
        $this->call(migrate_payment_data::class);
        $this->call(migrate_lpo_data::class);
        $this->call(migrate_invoice_data::class);
        $this->call(migrate_mpesa_data::class);

        // do post data migration keys migration
        $this->call(post_data_migration_keys_migration::class);
        
    }
}
