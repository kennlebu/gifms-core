<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Anchu\Ftp\Facades\Ftp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use App\Models\MobilePaymentModels\MobilePayment;//signsheets
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\LPOModels\Lpo; 
use App\Models\LPOModels\LpoQuotation;

class MigrateFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Files';

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
        $ftp        = FTP::connection()->getDirListing();
        $ftp_mig    = FTP::connection("connection_migration")->getDirListing();

















        //invoices
        $invoices               =   Invoice::all();
        $invoice_mig_folder     =   "invoices";
        $invoice_folder         =   "invoices";

        foreach ($invoices as $key => $value) {
            if ($value["invoice_document"]!='') {


                FTP::connection()->makeDir("./$invoice_folder/".$value["id"]);
                FTP::connection()->makeDir("./$invoice_folder/".$value["id"]);
                $file_contents   =   FTP::connection("connection_migration")
                                    ->readFile("$invoice_mig_folder"."/".$value["invoice_document"]);

                Storage::put("$invoice_folder"."/".$value["invoice_document"], $file_contents);

                // echo storage_path("$invoice_folder"."/".$value["invoice_document"])."\n";

                FTP::connection()->uploadFile(storage_path("app/$invoice_folder"."/".$value["invoice_document"]), './invoices/'.$value["id"].'/'.$value["invoice_document"]);

                echo "Invoice Document ---------- ".$value["id"]."------".$value["invoice_document"]."\n";
            }
        }


















        //claims
        $claims               =   Claim::all();
        $claim_mig_folder     =   "claims";
        $claim_folder         =   "claims";

        foreach ($claims as $key => $value) {
            if ($value["claim_document"]!='') {


                FTP::connection()->makeDir("./$claim_folder/".$value["id"]);
                FTP::connection()->makeDir("./$claim_folder/".$value["id"]);
                $file_contents   =   FTP::connection("connection_migration")
                                    ->readFile("$claim_mig_folder"."/".$value["claim_document"]);

                Storage::put("$claim_folder"."/".$value["claim_document"], $file_contents);

                // echo storage_path("$claim_folder"."/".$value["claim_document"])."\n";

                FTP::connection()->uploadFile(storage_path("app/$claim_folder"."/".$value["claim_document"]), './claims/'.$value["id"].'/'.$value["claim_document"]);

                echo "Claim Document ---------- ".$value["id"]."------".$value["claim_document"]."\n";
            }
        }


















        //lpo_quotations
        $lpo_quotations               =   LpoQuotation::all();
        $lpo_quotation_mig_folder     =   "quotations";
        $lpo_quotation_folder         =   "lpo_quotations";

        foreach ($lpo_quotations as $key => $value) {
            if ($value["quotation_doc"]!=''&&$value["lpo_id"]!='' ) {


                FTP::connection()->makeDir("./lpos/".$value["lpo_id"]."/quotations/".$value["id"]);
                FTP::connection()->makeDir("./lpos/".$value["lpo_id"]."/quotations/".$value["id"]);
                $file_contents   =   FTP::connection("connection_migration")
                                    ->readFile("$lpo_quotation_mig_folder"."/".$value["quotation_doc"]);

                Storage::put("$lpo_quotation_folder"."/".$value["quotation_doc"], $file_contents);

                // echo storage_path("$lpo_quotation_folder"."/".$value["quotation_doc"])."\n";

                FTP::connection()->uploadFile(storage_path("app/$lpo_quotation_folder"."/".$value["quotation_doc"]), './lpos/'.$value["lpo_id"].'/quotations/'.$value["id"].'/'.$value["quotation_doc"]);

                echo "Lpo Quotation Document -----lpo-".$value["lpo_id"]."----- ".$value["id"]."------".$value["quotation_doc"]."\n";
            }
        }


















        //mobile_payments
        $mobile_payments               =   MobilePayment::all();
        $mobile_payment_mig_folder     =   "signsheets";
        $mobile_payment_folder         =   "mobile_payments";

        foreach ($mobile_payments as $key => $value) {
            if ($value["attendance_sheet"]!='') {


                FTP::connection()->makeDir("./$mobile_payment_folder/".$value["id"]."/signsheet/");
                FTP::connection()->makeDir("./$mobile_payment_folder/".$value["id"]."/signsheet/");
                $file_contents   =   FTP::connection("connection_migration")
                                    ->readFile("$mobile_payment_mig_folder"."/".$value["attendance_sheet"]);

                Storage::put("$mobile_payment_folder"."/".$value["attendance_sheet"], $file_contents);

                // echo storage_path("$mobile_payment_folder"."/".$value["attendance_sheet"])."\n";

                FTP::connection()->uploadFile(storage_path("app/$mobile_payment_folder"."/".$value["attendance_sheet"]), './mobile_payments/'.$value["id"].'/signsheet/'.$value["attendance_sheet"]);

                echo "Mobile Payments Signsheet ---------- ".$value["id"]."------".$value["attendance_sheet"]."\n";
            }
        }














    }
}
