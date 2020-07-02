<?php

namespace App\Console\Commands;

use App\Models\PaymentModels\VoucherNumber;
use Illuminate\Console\Command;

class RemoveVoucherDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voucher:remove-duplicates {payable_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes voucher number duplicates that cause skipping';

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
        // $this->argument('payable_id')
        $this->info('Running...');

        $tobedeleted = [];
        $start = VoucherNumber::where('payable_id', '>=', $this->argument('payable_id'))->first();
        VoucherNumber::where('id', '>=', $start->id)->chunk(50, function($voucher_numbers) use (&$tobedeleted) {
            foreach($voucher_numbers as $voucher_number){
                $duplicates = VoucherNumber::where('payable_id', $voucher_number->payable_id)->where('payable_type', $voucher_number->payable_type)->orderBy('id', 'asc')->get();
                if(count($duplicates) > 1){
                    for($i = 1; $i < count($duplicates); $i++){
                        if(!in_array($duplicates[$i]->id, $tobedeleted)){
                            $tobedeleted[] = $duplicates[$i]->id;
                        }
                    }
                }
            }
        });

        $this->info('Found '. count($tobedeleted). ' duplicates');

        VoucherNumber::destroy($tobedeleted);

        $this->info('Deleted duplicates. Rearranging voucher numbers...');

        VoucherNumber::where('id', '>=', $start->id)->orderBy('id', 'asc')->chunk(50, function($voucher_numbers) {
            foreach($voucher_numbers as $voucher_number){
                $voucher_number->voucher_number = $voucher_number->generated_voucher_from_previous();
                $voucher_number->disableLogging();
                $voucher_number->save();
            }
        });
        
        $this->info('Done');
    }
}
