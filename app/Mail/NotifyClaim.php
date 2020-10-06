<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ClaimsModels\Claim;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;
use Config;

class NotifyClaim extends Mailable
{
    use Queueable, SerializesModels;

    protected $claim;
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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ccs = [] ;

        $this->view('emails/notify_claim')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address']
                ]);

        if($this->claim->status_id == 10){
            $ccs[] = $this->claim->requested_by;
            $to = Staff::whereHas('roles', function($query){
                $query->where('role_id', 8);  
            })->get();

            return $this->to($to)
                    ->with([
                            'claim' => $this->claim,
                            // 'addressed_to' => $this->accountant,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->cc($ccs)
                    ->subject("Claim Approval Request ".$this->claim->ref);
        }else if($this->claim->status_id == 12){

            $to = Staff::whereHas('roles', function($query){
                $query->where('role_id', 13);  
            })->get();
            return $this->to($to)
                    ->with([
                            'claim' => $this->claim,
                            // 'addressed_to' => $this->financial_controller,
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

            $to = Staff::whereHas('roles', function($query){
                $query->where('role_id', 5);  
            })->get();
            return $this->to($to)
                    ->with([
                            'claim' => $this->claim,
                            // 'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Claim Approval Request ".$this->claim->ref);
        }else if($this->claim->status_id == 4){

            $to = Staff::whereHas('roles', function($query){
                $query->whereIn('role_id', [3,4]);  
            })->get();
            return $this->to($to)
                    ->with([
                            'claim' => $this->claim,
                            // 'addressed_to' => $this->director,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Claim Approval Request ".$this->claim->ref);
        }else if($this->claim->status_id == 5){

            $to = Staff::whereHas('roles', function($query){
                $query->where('role_id', 5);  
            })->get();
            return $this->to($to)
                    ->with([
                            'claim' => $this->claim,
                            // 'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Claim Approval Request ".$this->claim->ref);
        }

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
