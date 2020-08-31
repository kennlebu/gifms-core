<?php

namespace App\Jobs;

use App\Models\AdvancesModels\Advance;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\PaymentModels\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class completeBatchUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $payment_ids = [];
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payment_ids, $user)
    {
        $this->payment_ids = $payment_ids;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get the payments and move them to the next status
        foreach ($this->payment_ids as $payment_id) {
            $payment = Payment::find($payment_id);
            $payment->status_id = 3;
            $payment->disableLogging();
            $payment->save();

            // Now update the invoices, claims and advances
            if($payment->payable_type == 'invoices'){
                $invoice = Invoice::find($payment->payable_id);
                if($invoice->status_id == 5){
                    $invoice->status_id = 7;
                    $invoice->disableLogging();
                    $invoice->save();
                    activity()
                        ->performedOn($invoice)
                        ->causedBy($this->user)
                        ->log('Uploaded payment to bank');
                }
            }
            elseif($payment->payable_type == 'advances'){
                $advance = Advance::find($payment->payable_id);
                if($advance->status_id == 5){
                    $advance->status_id = 7;
                    $advance->disableLogging();
                    $advance->save();
                    activity()
                        ->performedOn($advance)
                        ->causedBy($this->user)
                        ->log('Uploaded payment to bank');
                }                    
            }
            elseif($payment->payable_type == 'claims'){
                $claim = Claim::find($payment->payable_id);
                if($claim->status_id == 5 || $claim->status_id == 6){
                    $claim->status_id = 7;
                    $claim->disableLogging();
                    $claim->save();
                    activity()
                        ->performedOn($claim)
                        ->causedBy($this->user)
                        ->log('Uploaded payment to bank');
                }                    
            }
        }
    }
}
