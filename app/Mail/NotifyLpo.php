<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\LPOModels\Lpo;

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
        $this->lpo = $lpo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails/submit_lpo')
                ->to($this->lpo->project_manager)
                ->with([
                        'lpo' => $this->lpo,
                    ])
                ->subject("LPO Approval Request ".$this->lpo->ref);
    }
}
