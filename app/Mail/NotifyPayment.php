<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AdvancesModels\Advance;
use App\Models\ClaimsModels\Claim;
use App\Models\InvoicesModels\Invoice;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\StaffModels\Staff;
use Config;

class NotifyPayment extends Mailable
{
    use Queueable, SerializesModels;

    protected $payable;
    protected $accountant;
    protected $payable_type;
    // protected $director;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payable, $payable_type)
    {
        $this->payable_type = $payable_type;
        $this->payable = $payable;
        
        $this->accountant           = Staff::findOrFail(    (int)   Config::get('app.accountant_id'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ccs = [] ;
        $ccs[0] = $this->accountant->email;

        $this->view('emails/notify_payment')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ]);  

        if($this->payable_type == 'advances'){

            return $this->to($this->payable->requested_by->email)
                    ->with([
                            'payable_type' => $this->payable_type,
                            'payable' => $this->payable,
                            'addressed_to' => $this->payable->requested_by->full_name,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->cc($ccs)
                    ->subject("Payment for advance ".$this->payable->ref);

        }
        else if($this->payable_type == 'claims'){

            return $this->to($this->payable->requested_by->email)
                    ->with([
                            'payable_type' => $this->payable_type,
                            'payable' => $this->payable,
                            'addressed_to' => $this->payable->requested_by->full_name,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Payment for claim ".$this->payable->ref);

        }
        else if($this->payable_type == 'mobile_payments'){

            return $this->to($this->payable->requested_by->email)
                    ->with([
                            'payable_type' => $this->payable_type,
                            'payable' => $this->payable,
                            'addressed_to' => $this->payable->requested_by->full_name,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Payment for mobile payment ".$this->payable->ref);

        }
        else if($this->payable_type == 'invoices'){

            return $this->to($this->payable->supplier->email)
                    ->with([
                        'payable_type' => $this->payable_type,
                        'payable' => $this->payable,
                        'addressed_to' => $this->payable->supplier->supplier_name,
                        'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Payment for invoice ".$this->payable->external_ref);
        }

    }
}
