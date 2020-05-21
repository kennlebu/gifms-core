<?php

namespace App\Console\Commands;

use App\Models\FinanceModels\TaxRate;
use App\Models\InvoicesModels\Invoice;
use App\Models\LPOModels\Lpo;
use App\Models\MobilePaymentModels\MobilePayment;
use Illuminate\Console\Command;

class PopuateVatPercentage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refractor:vat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds VAT percentages to transactions.';

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
        $tax_rate = TaxRate::where('charge', 'VAT')->first();
        $start_date = $tax_rate->created_at;
        $today = date('Y-m-d');
        $percentage = $tax_rate->rate;

        $this->info("Invoices");
        $invoices = Invoice::whereBetween('created_at', [$start_date, $today])->update(['vat_percentage'=>$percentage]);

        $this->info("LPOs");
        $lpos = Lpo::whereBetween('created_at', [$start_date, $today])->update(['vat_percentage'=>$percentage]);

        $this->info("Mobile Payments");
        $mps = MobilePayment::whereBetween('created_at', [$start_date, $today])->update(['vat_percentage'=>$percentage]);

        $this->info("Done");
    }
}
