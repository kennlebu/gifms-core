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
        $this->call(migrate_referrence_tables::class);
        $this->call(migrate_approvals_table::class);


        //accounting
        $this->call(migrate_departments_table::class);
        $this->call(migrate_banking_data::class);
        $this->call(migrate_lpo_data::class);
    }
}
