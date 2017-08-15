<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ClaimsModels\Claim;
use App\Models\StaffModels\Staff;
use Config;

class NotifyClaim extends Mailable
{
    use Queueable, SerializesModels;

    protected $claim;
    protected $accountant;
    protected $financial_controller;
    protected $director;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Claim $claim)
    {

            $this->claim   = Claim::with( 
                                        'requested_by',
                                        'request_action_by',
                                        'project',
                                        'status',
                                        'project_manager',
                                        'currency',
                                        'rejected_by',
                                        'approvals',
                                        'allocations'
                                    )->findOrFail($claim->id);
        foreach ($this->claim->approvals as $key => $value) {
            $this->claim->approvals[$key]['approver'] = Staff::find($this->claim->approvals[$key]['approver_id']);
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
