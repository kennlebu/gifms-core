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

        $bccs = [] ;
        $bccs[0] = $this->accountant;
        $bccs[1] = $this->financial_controller;
        $bccs[2] = $this->director;


        $this->view('emails/notify_claim')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ])           
            ->cc($this->claim->requested_by)       
            ->bcc($bccs);











        if($this->claim->status_id == 10){



            return $this->to($this->accountant)
                    ->with([
                            'claim' => $this->claim,
                            'addressed_to' => $this->accountant,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Claim Approval Request ".$this->claim->ref);
        }else if($this->claim->status_id == 2){



            return $this->to($this->claim->project_manager)
                    ->with([
                            'claim' => $this->claim,
                            'addressed_to' => $this->claim->project_manager,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Claim Approval Request ".$this->claim->ref);
        }else if($this->claim->status_id == 3){



            return $this->to($this->financial_controller)
                    ->with([
                            'claim' => $this->claim,
                            'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Claim Approval Request ".$this->claim->ref);
        }else if($this->claim->status_id == 4){



            return $this->to($this->director)
                    ->with([
                            'claim' => $this->claim,
                            'addressed_to' => $this->director,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Claim Approval Request ".$this->claim->ref);
        }







        // else if($this->claim->status_id == 99){



        //     return $this->to($this->claim->requested_by)
        //             ->with([
        //                     'claim' => $this->claim,
        //                     'addressed_to' => $this->claim->requested_by,
        //                     'js_url' => Config::get('app.js_url'),
        //                 ])
        //             ->subject("Claim Cancelled ".$this->claim->ref);
        // }

        else if($this->claim->status_id == 9){



            return $this->to($this->claim->requested_by)
                    ->with([
                            'claim' => $this->claim,
                            'addressed_to' => $this->claim->requested_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Claim Rejected ".$this->claim->ref);
        }

    }
}
