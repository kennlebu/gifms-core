<?php

namespace App\Console\Commands;

use App\Models\SuppliesModels\SupplierServiceType;
use App\Models\SuppliesModels\SupplyCategory;
use App\Models\SuppliesModels\SupplyCategoryService;
use Illuminate\Console\Command;

class RefractorSupplierServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refractor:supplier-services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refractors supplier services';

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
        $categories = SupplyCategory::all();
        foreach($categories as $cat) {
            if(!empty($cat->service_type)) {
                $service_type = SupplierServiceType::where('service_type', $cat->service_type)->first();
                SupplyCategoryService::create(['supply_category_id' => $cat->id, 'supplier_service_type_id' => $service_type->id]);
            }
        }
        $this->info("Done!");
    }
}
