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
    protected $accountant;
    protected $financial_controller;
    protected $director;
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

        $bccs = [] ;
        $bccs[0] = $this->accountant;
        $bccs[1] = $this->financial_controller;
        $bccs[2] = $this->director;


        $this->view('emails/notify_lpo')         
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
