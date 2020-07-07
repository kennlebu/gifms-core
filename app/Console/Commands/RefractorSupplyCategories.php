<?php

namespace App\Console\Commands;

use App\Models\SuppliesModels\Supplier;
use App\Models\SuppliesModels\SupplierSupplyCategory;
use Illuminate\Console\Command;

class RefractorSupplyCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refractor:supply_categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refractors supply categories. Use once';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Populating pivot table...');
        Supplier::chunk(100, function($suppliers) {
            foreach($suppliers as $supplier){
                if(!empty($supplier->supply_category_id)){
                    SupplierSupplyCategory::create([
                        'supplier_id' => $supplier->id,
                        'supply_category_id' => $supplier->supply_category_id
                    ]);
                }
            }
        });
        $this->info('Done');
    }
}
