<?php

namespace App\Mail;

use App\Models\Requisitions\Requisition;
use App\Models\StaffModels\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\StaffModels\User;
use Config;

class NotifyRequisition extends Mailable
{
    use Queueable, SerializesModels;
    protected $requisition;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($requisition_id)
    {
        $this->requisition = Requisition::with('status','allocations.objective','requested_by','program_manager','items.supplier_service','items.status',
                                        'allocations.project','allocations.account','logs.causer','approvals.approver','lpos.status','items.county','returned_by')
                                        ->find($requisition_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $to = '';
        $ccs = [];
        $subject = 'Requisition';

        $this->view('emails/notify_requisition')
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address']
                ]);

        if($this->requisition->status_id == 2){     // Submission for approval
            $to = $this->requisition->program_manager;
            $ccs[] = $this->requisition->requested_by;
            $subject = 'Requisition approval request';
            
            return $this->cc($ccs)
                        ->to($to)
                        ->with([
                                'requisition' => $this->requisition,
                                'addressed_to' => $this->requisition->program_manager,
                                'js_url' => Config::get('app.js_url')
                            ])
                        ->subject($subject);

        }
        elseif($this->requisition->status_id == 3 || $this->requisition->status_id == 4){
            $to = $this->requisition->requested_by;
            $ccs[] = $this->requisition->program_manager;

            // Add Admin Manager to cc
            $admin_manager = Staff::whereHas('roles', function($query){
                $query->where('name', 'admin-manager');  
            })->get();
            foreach($admin_manager as $am){
                $ccs[] = $am;
            }
            $subject = 'Requisition approval';

            return $this->cc($ccs)
                        ->to($to)
                        ->with([
                                'requisition' => $this->requisition,
                                'addressed_to' => $this->requisition->requested_by,
                                'js_url' => Config::get('app.js_url')
                            ])
                        ->subject($subject);
        }
        else return null;


        
    }
}
