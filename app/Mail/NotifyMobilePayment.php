<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\StaffModels\Staff;
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
                                'project',
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


        $this->accountant           = Staff::findOrFail(    (int)   Config::get('app.accountant_id'));
        $this->financial_controller = Staff::findOrFail(    (int)   Config::get('app.financial_controller_id'));
        $this->director             = Staff::findOrFail(    (int)   Config::get('app.director_id'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
