<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\StaffModels\Staff;
use Config;

class RequestMPBankSigning extends Mailable
{
    use Queueable, SerializesModels;
    protected $mobile_payment_id;
    protected $mobile_payment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mobile_payment_id)
    {
        $this->mobile_payment_id = $mobile_payment_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {      
        // Recepients
        $directors = Staff::withRole('director')->get();
        $rosemary = Staff::find(42); //TODO: Make this dynamic
        $to = [];
        $ccs = [];
        foreach($directors as $dir){
            $to[] = $dir->email;
        }
        $to[] = $rosemary->email;

        $accountant = Staff::withRole('accountant')->get();
        foreach($accountant as $acc){
            $ccs[] = $acc->email;
        }

        $finance = Staff::withRole('financial-controller')->get();
        foreach($finance as $fin){
            $ccs[] = $fin->email;
        }

        $this->mobile_payment = MobilePayment::with('approvals','project_manager','currency','requested_by')->find($this->mobile_payment_id);

        $this->view('emails/request_mp_bank_signing')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],
                ]);

        return $this->to($to)
                ->with([
                        'mobile_payment' => $this->mobile_payment,
                        'js_url' => Config::get('app.js_url')
                    ])
                ->cc($ccs)
                ->subject("Processed mobile payment uploaded to bank. Mobile Payment Ref: ".$this->mobile_payment->ref);
    }
}
