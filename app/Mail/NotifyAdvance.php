<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\AdvancesModels\Advance;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;
use Config;

class NotifyAdvance extends Mailable
{
    use Queueable, SerializesModels;

    protected $advance;
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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $ccs = [] ;

        $this->view('emails/notify_advance')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ]);  

        if($this->advance->status_id == 13){
            $ccs[] = $this->advance->requested_by;

            $to = Staff::whereHas('roles', function($query){
                $query->where('role_id', 8);  
            })->get();
            return $this->to($to)
                    ->with([
                            'advance' => $this->advance,
                            // 'addressed_to' => $this->accountant,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->cc($ccs)
                    ->subject("Advance Approval Request ".$this->advance->ref);
        }else if($this->advance->status_id == 14){

            $to = Staff::whereHas('roles', function($query){
                $query->where('role_id', 13);  
            })->get();
            return $this->to($to)
                    ->with([
                            'advance' => $this->advance,
                            // 'addressed_to' => $this->financial_controller,
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

            $to = Staff::whereHas('roles', function($query){
                $query->where('role_id', 5);  
            })->get();
            return $this->to($to)
                    ->with([
                            'advance' => $this->advance,
                            // 'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Advance Approval Request ".$this->advance->ref);
        }else if($this->advance->status_id == 4){

            $to = Staff::whereHas('roles', function($query){
                $query->whereIn('role_id', [3,4]);  
            })->get();
            return $this->to($to)
                    ->with([
                            'advance' => $this->advance,
                            // 'addressed_to' => $this->director,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Advance Approval Request ".$this->advance->ref);
        }else if($this->advance->status_id == 8){

            $to = Staff::whereHas('roles', function($query){
                $query->where('role_id', 5);  
            })->get();
            return $this->to($to)
                    ->with([
                            'advance' => $this->advance,
                            // 'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Advance Approval Request ".$this->advance->ref);
        }

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
