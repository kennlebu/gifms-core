<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AdvanceModels\Advance;
use App\Models\StaffModels\Staff;
use Config;

class NotifyAdvance extends Mailable
{
    use Queueable, SerializesModels;

    protected $advance;
    protected $accountant;
    protected $financial_controller;
    protected $director;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Advance $advance)
    {

        $this->advance   = Advance::with(
                                'requested_by',
                                'request_action_by',
                                'project',
                                'status',
                                'project_manager',
                                'currency',
                                'rejected_by',
                                'approvals',
                                'allocations'
                            )->findOrFail($advance->id);
        foreach ($this->advance->approvals as $key => $value) {
            $this->advance->approvals[$key]['approver'] = Staff::find($this->advance->approvals[$key]['approver_id']);
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
