<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PaymentModels\PaymentBatch;
use App\Models\PaymentModels\Payment;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;
use Config;

class RequestBankSigning extends Mailable
{
    use Queueable, SerializesModels;
    protected $batch_id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($batch_id)
    {
        $this->batch_id = $batch_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {      
        // Recepients
        // $accountant = Staff::findOrFail((int) Config::get('app.accountant_id'));
        $directors = Staff::whereHas('roles', function($query){
            $query->whereIn('role_id', [3,4]);  
        })->get();
        // $rosemary = Staff::find(42); //TODO: Make this dynamic
        $to = [];
        $ccs = [];
        foreach($directors as $dir){
            $to[] = $dir->email;
        }
        // $to[] = $rosemary->email;

        $finance = Staff::whereHas('roles', function($query){
            $query->where('role_id', 5);  
        })->get();
        foreach($finance as $fin){
            $ccs[] = $fin->email;
        }

        $fr = Staff::whereHas('roles', function($query){
            $query->where('role_id', 13);  
        })->get();
        foreach($fr as $finr){
            $ccs[] = $finr->email;
        }

        $accountant = Staff::whereHas('roles', function($query){
            $query->where('role_id', 8);  
        })->get();
        foreach($accountant as $acc){
            $ccs[] = $acc->email;
        }
        // $ccs[] = $accountant;

        $payments = Payment::with('currency')->where('payment_batch_id', $this->batch_id)->get();
        $batch = PaymentBatch::find($this->batch_id);
        $total_kes = 0;
        $total_usd = 0;
        foreach($payments as $payment){
            if($payment->currency_id == 1) $total_kes += $payment->amount;
            elseif($payment->currency_id == 2) $total_usd += $payment->amount;
        }

        $this->view('emails/request_bank_signing')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],
                ]);

        return $this->to($to)
                ->with([
                        'payments' => $payments,
                        'batch' => $batch,
                        'total_kes' => $total_kes,
                        'total_usd'=> $total_usd,
                        'js_url' => Config::get('app.js_url'),
                    ])
                ->cc($ccs)
                ->subject("Processed payments uploaded to bank. Batch Ref: ".$batch->ref);
    }
}
