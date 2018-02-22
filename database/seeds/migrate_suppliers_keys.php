<?php

use Illuminate\Database\Seeder;

class migrate_suppliers_keys extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        /**
         * 
         * 
         * 
         * 
         * 
         *                  Suppliers
         * 
         * 
         * ensure banking keys migrated
         * 
         */




        $migrate_keys_sql = "
                                UPDATE suppliers sup 
                                    LEFT JOIN banks b 
                                    ON b.bank_code = sup.migration_bank_id
                                    LEFT JOIN bank_branches bb 
                                    ON bb.branch_code = sup.migration_bank_branch_code
                                    LEFT JOIN staff s 
                                    ON s.migration_id = sup.migration_staff_id

                                    SET     sup.bank_id             =   b.id ,
                                            sup.bank_branch_id      =   bb.id,
                                            sup.staff_id            =   s.id
                             ";

        DB::statement($migrate_keys_sql);

        echo "\n __________Migrated suppliers Foreign keys ---------- banks,bank_branches \n";









        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  SupplyCategoryItems
         * 
         * 
         * 
         * 
         * 
         */





        $migrate_keys_sql = "
                                UPDATE supply_category_items sci 
                                    LEFT JOIN supply_categories sc 
                                    ON sc.migration_id = sci.migration_category_id

                                    SET     sci.category_id        =   sc.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated supply_category_items Foreign keys ---------- supply_categories \n";

















        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  SupplierRates
         * 
         * 
         * 
         * 
         * 
         */




        $migrate_keys_sql = "
                                UPDATE supplier_rates sr 
                                    LEFT JOIN supply_category_items sci 
                                    ON sci.migration_id = sr.migration_category_item_id

                                    SET     sr.category_item_id        =   sci.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated supplier_rates Foreign keys ---------- supply_category_items \n";























        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *                  category_suppliers
         * 
         * 
         * 
         * 
         * 
         */




        $migrate_keys_sql = "
                                UPDATE category_suppliers cs 
                                    LEFT JOIN supply_categories sc 
                                    ON sc.migration_id = cs.migration_category_id
                                    LEFT JOIN suppliers s 
                                    ON s.migration_id = cs.migration_category_id

                                    SET     cs.category_id         =   sc.id ,
                                            cs.supplier_id              =   s.id 
                             ";

        DB::statement($migrate_keys_sql);

       echo "\n __________Migrated category_suppliers Foreign keys ---------- supply_categories, suppliers\n";














    }
}
