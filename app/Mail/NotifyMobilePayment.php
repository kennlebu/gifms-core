<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;
use Config;

class NotifyMobilePayment extends Mailable
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

        $this->mobile_payment   = MobilePayment::with(
                                'requested_by',
                                'requested_action_by',
                                //'project',
                                'account',
                                'mobile_payment_type',
                                'invoice',
                                'status',
                                'project_manager',
                                'region',
                                'county',
                                'currency',
                                'rejected_by',
                                'payees_upload_mode',
                                'payees',
                                'approvals',
                                'allocations'
                            )->findOrFail($mobile_payment->id);
        foreach ($this->mobile_payment->approvals as $key => $value) {
            $this->mobile_payment->approvals[$key]['approver'] = Staff::find($this->mobile_payment->approvals[$key]['approver_id']);
        }

        // $this->accountant           = Staff::findOrFail(    (int)   Config::get('app.accountant_id'));
        // $this->financial_controller = Staff::findOrFail(    (int)   Config::get('app.financial_controller_id'));
        // $this->director             = Staff::findOrFail(    (int)   Config::get('app.director_id'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ccs = [] ;
        $this->view('emails/notify_mobile_payment')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ]);
                
        if($this->mobile_payment->status_id == 9 || $this->mobile_payment->status_id == 12){
            $ccs[] = $this->mobile_payment->requested_by;
            $to = User::withRole('accountant')->get();

            return $this->to($to)
                    ->with([
                            'mobile_payment' => $this->mobile_payment,
                            // 'addressed_to' => $to,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->cc($ccs)
                    ->subject("Mobile Payment Approval Request ".$this->mobile_payment->ref);
        }else if($this->mobile_payment->status_id == 2){

            return $this->to($this->mobile_payment->project_manager)
                    ->with([
                            'mobile_payment' => $this->mobile_payment,
                            'addressed_to' => $this->mobile_payment->project_manager,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Mobile Payment Approval Request ".$this->mobile_payment->ref);
        }else if($this->mobile_payment->status_id == 3){
            $to = User::withRole('financial-controller')->get();

            return $this->to($to)
                    ->with([
                            'mobile_payment' => $this->mobile_payment,
                            // 'addressed_to' => $to,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Mobile Payment Approval Request ".$this->mobile_payment->ref);
        }else if($this->mobile_payment->status_id == 4){

            $to = User::withRole('director')->get();
            return $this->to($to)
                    ->with([
                            'mobile_payment' => $this->mobile_payment,
                            // 'addressed_to' => $to,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Mobile Payment Approval Request ".$this->mobile_payment->ref);
        }

        else if($this->mobile_payment->status_id == 7){

            return $this->to($this->mobile_payment->requested_by)
                    ->with([
                            'mobile_payment' => $this->mobile_payment,
                            'addressed_to' => $this->mobile_payment->requested_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Mobile Payment Rejected ".$this->mobile_payment->ref);
        }

        // else if($this->mobile_payment->status_id == 11){

        //     return $this->to($this->accountant)
        //             ->with([
        //                     'mobile_payment' => $this->mobile_payment,
        //                     'addressed_to' => $this->accountant,
        //                     'js_url' => Config::get('app.js_url'),
        //                 ])
        //             // ->cc($ccs)
        //             ->subject("Corrected Mobile Payment Approval Request ".$this->mobile_payment->ref);
        // }

    }
}
