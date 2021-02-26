<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Config;
use Illuminate\Support\Facades\Storage;

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
    protected $attachment = null;
    protected $filename = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_to, $ccs, $m_subject, $title, $paragraphs, $m_signature, $show_app_url, $attachment=null, $filename=null)
    {
        $this->mail_to = $mail_to;
        $this->ccs = $ccs;
        $this->m_subject = $m_subject;
        $this->title = $title;
        $this->paragraphs = $paragraphs;
        $this->m_signature = $m_signature;
        if($show_app_url) {
            $this->js_url = Config::get('app.js_url');
        }
        $this->attachment = $attachment;
        $this->filename = $filename;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
        if (!empty($this->attachment) && Storage::exists('/email_attachments/'.$this->attachment)) {
            $file = Storage::get('/email_attachments/'.$this->attachment);
            $this->attachData($file, $this->filename ? $this->filename : $this->attachment);
        }
        
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
