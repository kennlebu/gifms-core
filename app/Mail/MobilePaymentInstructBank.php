<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\StaffModels\Staff;
use Config;

class MobilePaymentInstructBank extends Mailable
{
    use Queueable, SerializesModels;

    protected $mobile_payment;
    protected $accountant;
    protected $financial_controller;
    protected $director;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MobilePayment $mobile_payment)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $bccs = [] ;
        $bccs[0] = $this->accountant;
        $bccs[1] = $this->financial_controller;
        $bccs[2] = $this->director;


        $this->view('emails/mobile_payment_instruct_bank')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ])           
            ->cc($this->lpo->requested_by)       
            ->bcc($bccs);











        if($this->lpo->status_id == 13){



            return $this->to($this->accountant)
                    ->with([
                            'lpo' => $this->lpo,
                            'addressed_to' => $this->accountant,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Approval Request ".$this->lpo->ref);
        }else if($this->lpo->status_id == 3){



            return $this->to($this->lpo->project_manager)
                    ->with([
                            'lpo' => $this->lpo,
                            'addressed_to' => $this->lpo->project_manager,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Approval Request ".$this->lpo->ref);
        }else if($this->lpo->status_id == 4){



            return $this->to($this->financial_controller)
                    ->with([
                            'lpo' => $this->lpo,
                            'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Approval Request ".$this->lpo->ref);
        }else if($this->lpo->status_id == 5){



            return $this->to($this->director)
                    ->with([
                            'lpo' => $this->lpo,
                            'addressed_to' => $this->director,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Approval Request ".$this->lpo->ref);
        }







        else if($this->lpo->status_id == 11){



            return $this->to($this->lpo->requested_by)
                    ->with([
                            'lpo' => $this->lpo,
                            'addressed_to' => $this->lpo->requested_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Cancelled ".$this->lpo->ref);
        }else if($this->lpo->status_id == 12){



            return $this->to($this->lpo->requested_by)
                    ->with([
                            'lpo' => $this->lpo,
                            'addressed_to' => $this->lpo->requested_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Rejected ".$this->lpo->ref);
        }

    }
}
