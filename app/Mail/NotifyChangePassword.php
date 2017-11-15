<?php

namespace App\Mail;

use JWTAuth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AdvancesModels\Advance;
use App\Models\StaffModels\Staff;
use Config;

class NotifyChangePassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $password;
    protected $staff;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Staff $stf, String $pwd)
    {
        $this->password     = $pwd;
        $this->staff        = $stf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

            return $this->view('emails/notify_change_password')
                    ->replyTo([
                            'email' => Config::get('mail.reply_to')['address'],

                        ])
                    ->to($this->staff)
                    ->with([
                            'password' => $this->password,
                            'addressed_to' => $this->staff,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("New Password");


    }
}
