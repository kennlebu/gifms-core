<?php

namespace App\Console\Commands;

use App\Models\PaymentModels\VoucherNumber;
use Illuminate\Console\Command;

class RestoreVoucherNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voucher:restore {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restores previously removed voucher numbers and realigns the voucher numbers created after the previous removal';

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
        $start = VoucherNumber::find($this->argument('id'));
        if(empty($start)){
            $this->error('Voucher number not found');
        }
        else {
            VoucherNumber::where('id', '>=', $start->id)->chunk(50, function($voucher_numbers) {
                foreach($voucher_numbers as $voucher_number){
                    $voucher_number->voucher_number = $voucher_number->generated_voucher_from_previous();
                    $voucher_number->disableLogging();
                    $voucher_number->save();
                }
            });
            $this->info("Done");
        }
    }
}
