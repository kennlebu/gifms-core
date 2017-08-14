<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\LPOModels\Lpo;
use App\Models\StaffModels\Staff;
use Config;

class NotifyLpo extends Mailable
{
    use Queueable, SerializesModels;

    protected $lpo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LPO $lpo)
    {
        //
        $this->lpo = LPO::with(
                                            'requested_by',
                                            'request_action_by',
                                            'project',
                                            'account',
                                            'invoice',
                                            'status',
                                            'project_manager',
                                            'rejected_by',
                                            'cancelled_by',
                                            'received_by',
                                            'supplier',
                                            'currency',
                                            'quotations',
                                            'preffered_quotation',
                                            'items',
                                            'terms',
                                            'approvals',
                                            'deliveries'
                                )->findOrFail($lpo->id);
        foreach ($this->lpo->approvals as $key => $value) {
            $this->lpo->approvals[$key]['approver'] = Staff::findOrFail($this->lpo->approvals[$key]['approver_id']);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails/notify_lpo')
                ->to($this->lpo->project_manager)
                ->with([
                        'lpo' => $this->lpo,
                        'addressed_to' => $this->lpo->project_manager,
                        'js_url' => Config::get('app.js_url'),
                    ])
                ->subject("LPO Approval Request ".$this->lpo->ref);
    }
}
