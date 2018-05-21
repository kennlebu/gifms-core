<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PaymentModels\PaymentBatch;
use App\Models\PaymentModels\Payment;
use App\Models\StaffModels\Staff;
use Config;

class NotifyBatch extends Mailable
{
    use Queueable, SerializesModels;

    protected $accountant;
    protected $batch_id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payment_batch_id)
    {

        $this->accountant = Staff::findOrFail((int) Config::get('app.accountant_id'));
        $this->batch_id = $payment_batch_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ccs = [] ;
        $payment_batch = PaymentBatch::findOrFail($this->batch_id);
        $payments = Payment::where('payment_batch_id', $this->batch_id)->get();

        $this->view('emails/notify_batch')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ]);
        return $this->to($this->accountant->email)
                ->with([
                        'payments' => $payments,
                        'addressed_to' => $this->accountant,
                        'batch' => $payment_batch,
                        'js_url' => Config::get('app.js_url'),
                    ])
                
                ->subject("Payments Confirmed and Processed  ".$payment_batch->ref);
    }
}
