<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\LeaveManagementModels\LeaveRequest;
use App\Models\StaffModels\Staff;
use Config;

class NotifyLeaveRequest extends Mailable
{
    use Queueable, SerializesModels;

    protected $leave_request;
    protected $line_manager;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LeaveRequest $leave_request)
    {

            $this->leave_request = LeaveRequest::with( 
                                        'requested_by',
                                        'leave_type',
                                        'status',
                                        'line_manager',
                                        'rejected_by',
                                        'approvals'
                                    )->findOrFail($leave_request->id);
        foreach ($this->leave_request->approvals as $key => $value) {
            $this->leave_request->approvals[$key]['approver'] = Staff::find($this->leave_request->approvals[$key]['approver_id']);
        }

        $this->line_manager = Staff::findOrFail($leave_request->line_manager_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ccs = [] ;

        $this->view('emails/notify_leave_request')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ]);

        if($this->leave_request->status_id == 2){
            $ccs[0] = $this->leave_request->requested_by;

            return $this->to($this->line_manager)
                    ->with([
                            'leave_request' => $this->leave_request,
                            'addressed_to' => $this->line_manager,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->cc($ccs)
                    ->subject("Leave Approval Request ".$this->leave_request->ref);
        }
        else if($this->leave_request->status_id == 3){
            $ccs[0] = $this->leave_request->line_manager;

            return $this->to($this->leave_request->requested_by)
                    ->with([
                            'leave_request' => $this->leave_request,
                            'addressed_to' => $this->leave_request->requested_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Leave Request Approved".$this->leave_request->ref);
        }
        else if($this->leave_request->status_id == 4){
            $ccs[0] = $this->leave_request->requested_by;

            return $this->to($this->leave_request->requested_by)
                    ->with([
                            'leave_request' => $this->leave_request,
                            'addressed_to' => $this->leave_request->requested_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Leave Request Rejected ".$this->leave_request->ref);
        }

    }
}
