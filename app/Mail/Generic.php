<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Config;

class Generic extends Mailable
{
    use Queueable, SerializesModels;
    
    protected $mail_to;
    protected $ccs = [];
    protected $m_subject = '';
    protected $title = '';
    protected $paragraphs = [];
    protected $m_signature = '';
    protected $show_app_url;
    protected $js_url = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_to, $ccs, $m_subject, $title, $paragraphs, $m_signature, $show_app_url)
    {
        $this->mail_to = $mail_to;
        $this->ccs = $ccs;
        $this->m_subject = $m_subject;
        $this->title = $title;
        $this->paragraphs = $paragraphs;
        $this->m_signature = $m_signature;
        if($show_app_url)
            $this->js_url = Config::get('app.js_url');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.generic')
                ->to($this->mail_to)
                ->cc($this->ccs)
                ->subject($this->m_subject)
                ->with([
                    'title' => $this->title,
                    'paragraphs' => $this->paragraphs,
                    'signature' => $this->m_signature,
                    'js_url' => $this->js_url
                ]);
    }
}
