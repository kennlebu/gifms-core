<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;
use Config;

class NotifyPayment extends Mailable
{
    use Queueable, SerializesModels;

    protected $payable;
    protected $accountant;
    protected $payable_type;
    protected $payment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payable, $payment, $type=null)
    {
        if(empty($type)){
            $this->payable_type = $payment->payable_type;
        }
        else {
            $this->payable_type = $payment;
        }
        $this->payable = $payable;    
        $this->payment = $payment;    
        $this->accountant = Staff::findOrFail((int) Config::get('app.accountant_id'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $ccs = [] ;

        // Add accountants to cc
        $ccs = Staff::whereHas('roles', function($query){
            $query->where('role_id', 8);  
        })->get();

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
                        'payment' => $this->payment,
                        'payable_type' => $this->payable_type,
                        'payable' => $this->payable,
                        'addressed_to' => $this->payable->supplier->supplier_name,
                        'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Payment for invoice ".$this->payable->external_ref);
        }

    }
}
