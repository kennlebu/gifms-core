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

        $bccs = [] ;
        $bccs[0] = $this->accountant;
        $bccs[1] = $this->financial_controller;
        $bccs[2] = $this->director;


        $this->view('emails/notify_advance')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ])           
            ->cc($this->advance->requested_by)       
            ->bcc($bccs);











        if($this->advance->status_id == 13){



            return $this->to($this->accountant)
                    ->with([
                            'advance' => $this->advance,
                            'addressed_to' => $this->accountant,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Advance Approval Request ".$this->advance->ref);
        }else if($this->advance->status_id == 2){



            return $this->to($this->advance->project_manager)
                    ->with([
                            'advance' => $this->advance,
                            'addressed_to' => $this->advance->project_manager,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Advance Approval Request ".$this->advance->ref);
        }else if($this->advance->status_id == 3){



            return $this->to($this->financial_controller)
                    ->with([
                            'advance' => $this->advance,
                            'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Advance Approval Request ".$this->advance->ref);
        }else if($this->advance->status_id == 4){



            return $this->to($this->director)
                    ->with([
                            'advance' => $this->advance,
                            'addressed_to' => $this->director,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Advance Approval Request ".$this->advance->ref);
        }







        // else if($this->advance->status_id == 99){



        //     return $this->to($this->advance->requested_by)
        //             ->with([
        //                     'advance' => $this->advance,
        //                     'addressed_to' => $this->advance->requested_by,
        //                     'js_url' => Config::get('app.js_url'),
        //                 ])
        //             ->subject("Advance Cancelled ".$this->advance->ref);
        // }
        else if($this->advance->status_id == 11){



            return $this->to($this->advance->requested_by)
                    ->with([
                            'advance' => $this->advance,
                            'addressed_to' => $this->advance->requested_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Advance Rejected ".$this->advance->ref);
        }

    }
    }
}
