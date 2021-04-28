<?php

namespace App\Console\Commands;

use App\Models\InvoicesModels\Invoice;
use App\Models\PaymentModels\VoucherNumberOld;
use Illuminate\Console\Command;

class GenerateOldPVs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:old-pvs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and saves old payment vouchers';

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
        $this->info('Generating PVs...');
        VoucherNumberOld::truncate();
        $data = [];

        // Invoices
        $invoices = Invoice::select('id', 'migration_id')->whereNotNull('migration_id')->where('migration_id', '!=', '')->get();
        foreach($invoices as $invoice) {
            $data[] = ['voucher_number' => 'CHAI'.$this->pad_($invoice->migration_id), 'payable_type' => 'invoices', 'payable_id' => $invoice->id];
        }

        VoucherNumberOld::insert($data);

        $this->info('DONE');
    }

    function pad_($data){
        if(strlen($data)<5){
            return str_repeat('0', 5-strlen($data)).$data;
        }
        else{
            return $data;
        }
    }
}
