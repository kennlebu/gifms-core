<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MobilePaymentModels\MobilePayment;
use Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Excel;

class MigrateMobilePaymentDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:mpdates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Mobile Payment Approval Dates';

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
        //


        // $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Allowances')->get();

        $data_to_update=array();
        $data = array();
        $path = '/reports/dates2.csv';
        $url            = storage_path("app".$path);
        // $file           = File::get($url);
        $handle = fopen($url, 'r');
        $header = false;
        while($csvLine = fgetcsv($handle, 1000, ',')){
            if ($header) {
                $header = false;
            } else {
                $row = array();
                $row['id'] = $csvLine[0];
                $row['date'] = $csvLine[1];
                array_push($data, $row);
            }
        }

        foreach ($data as $key => $value) {

            $data_to_update[$key]['management_approval_at']                = $data[$key]['date'];
            $data_to_update[$key]['migration_id'] 						   = $data[$key]['id'];


            echo "\n MPESA Payments -$key---";
        	echo $data[$key]['id'];
        }

        
        

        $insertBatchs = array_chunk($data_to_update, 500);
        foreach ($insertBatchs as $batch) {
            foreach($batch as $record){
                // file_put_contents ( "C://Users//Kenn//Desktop//debug.txt" , PHP_EOL.'ROW:: '.json_encode($batch) , FILE_APPEND);
                $mp = MobilePayment::where('migration_id', $record['migration_id'])->first();
                $mp->management_approval_at = $record['management_approval_at'];
                $mp->save();
            }
             echo "\n-------------------------------------------------------Batch updated\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";

        $this->info("Done!");
    }

}
