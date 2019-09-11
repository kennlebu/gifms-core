<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\LPOModels\Lpo;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;
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
                                            'invoices',
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
            $this->lpo->approvals[$key]['approver'] = Staff::find($this->lpo->approvals[$key]['approver_id']);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ccs = [] ;

        $this->view('emails/notify_lpo')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address']
                ]);

        if($this->lpo->status_id == 13){
            $ccs[] = $this->lpo->requested_by;
            $to = Staff::whereHas('roles', function($query){
                $query->where('role_id', 8);  
            })->get();

            return $this->to($to)
                    ->with([
                            'lpo' => $this->lpo,
                            // 'addressed_to' => $this->accountant,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->cc($ccs)
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

            $to = Staff::whereHas('roles', function($query){
                $query->where('role_id', 5);  
            })->get();
            return $this->to($to)
                    ->with([
                            'lpo' => $this->lpo,
                            // 'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Approval Request ".$this->lpo->ref);
        }else if($this->lpo->status_id == 5){

            $to = Staff::whereHas('roles', function($query){
                $query->whereIn('role_id', [3,4]);  
            })->get();
            return $this->to($to)
                    ->with([
                            'lpo' => $this->lpo,
                            // 'addressed_to' => $this->director,
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
