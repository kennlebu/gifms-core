<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Config;

class NotifyNewStaff extends Mailable
{
    use Queueable, SerializesModels;

    protected $staff;
    protected $default_pwd;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($staff, $default_pwd)
    {
        //
        $this->staff = $staff;
        $this->default_pwd = $default_pwd;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $this->view('emails/notify_new_staff')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ]);

            return $this->to($this->staff->email)
                    ->with([
                            'staff' => $this->staff,
                            'password' => $this->default_pwd,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Your CHAI GIFMS account");

    }
}
