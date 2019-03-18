<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ProgramModels\ProgramStaff;
use Config;

class NotifyActivityCreation extends Mailable
{
    use Queueable, SerializesModels;
    protected $activity;
    protected $staffs;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($activity)
    {

        $this->activity = $activity;
        $program_staffs = ProgramStaff::where('program_id', $activity->program_id)->get();
        foreach($program_staffs as $ps){
            $this->staffs[] = $ps->staff;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ccs = $this->staffs;

        return $this->view('emails/notify_activity_creation')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ])
            ->to($this->activity->program_manager)
            ->with([
                    'activity' => $this->activity,
                    'js_url' => Config::get('app.js_url'),
                ])
            ->cc($this->staffs)
            ->subject("New Activity created for ".$this->activity->program->program_name);
        
    }
}
