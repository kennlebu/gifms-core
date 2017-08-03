<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateTransactionDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:transactionDocs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate LPO,Mobile Payment, Payment csvs And Payment Vouchers';

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
    }
}
