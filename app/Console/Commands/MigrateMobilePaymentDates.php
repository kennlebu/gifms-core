<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MobilePaymentModels\MobilePayment;
use Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

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


        $data = DB::connection(env('DB_MIGRATE_FROM','sqlsrv'))->table('Allowances')->get();

        $data_to_update=array();

        foreach ($data as $key => $value) {

            $data_to_update[$key]['management_approval_at']                = $data[$key]['ManagementApprovalDate'];
            $data_to_update[$key]['migration_id'] 						   = $data[$key]['ID'];


            echo "\n MPESA Payments -$key---";
        	echo $data[$key]['Title'];
        }

        
        

        $insertBatchs = array_chunk($data_to_update, 500);
        foreach ($insertBatchs as $batch) {
            foreach($batch as $record){
                // file_put_contents ( "C://Users//Kenn//Desktop//debug.txt" , '\nSQL:: '.json_encode($batch) , FILE_APPEND);
                $mp = MobilePayment::where('migration_id', $record['migration_id'])->first();
                $mp->management_approval_at = $record['management_approval_at'];
                $mp->save();
            }
             echo "\n-------------------------------------------------------Batch updated\n";
        }

        echo "\n-----------------------------------------------------------------------------------------------------\n";

        $this->info("Done!");
    }

    // try{

    //     $handle = fopen($file, 'r');
    //     $header = true;
    //     while($csvLine = fgetcsv($handle, 1000, ',')){
    //         if ($header) {
    //             $header = false;
    //         } else {
    //             $ref = explode(" ", $csvLine[1])[0];
    //             array_push($mobile_payment_ids, (int)preg_replace("/[^0-9]/", "", $ref)); 
    //         }
    //     }
    //     // Get only unique values
    //     $mobile_payment_ids = array_unique($mobile_payment_ids);

        

    //     // Change status of the mobile payment(s) to paid
    //     foreach($mobile_payment_ids as $id){
    //         $mobile_payment = MobilePayment::findOrFail($id);
    //         $mobile_payment->status_id = 5;
    //         $mobile_payment->save();
    //         array_push($payment_refs, $mobile_payment->ref);

    //         // // Mark payees as paid
    //         // foreach($mobile_payment->payees as $pid){
    //         //     $payee = MobilePaymentPayee::findOrFail($pid);
    //         //     $payee->paid = 1;
    //         //     $payee->save();
    //         // }
    //     }

    //     return Response()->json(array('msg' => 'Success: Mobile Payment(s) reconciled','payments' => $payment_refs), 200);

    // }
    // catch (Exception $e){
    //     return response()->json(['error'=>$e->getMessage()], 500);
    // }

}
