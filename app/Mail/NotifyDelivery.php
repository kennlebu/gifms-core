<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\DeliveriesModels\Delivery;
use App\Models\StaffModels\Staff;
use App\Models\LPOModels\Lpo;
use App\Models\SuppliesModels\Supplier;
use App\Models\LPOModels\LpoQuotation;
use Config;

class NotifyDelivery extends Mailable
{
    use Queueable, SerializesModels;

    protected $delivery;
    protected $accountant;
    protected $financial_controller;
    protected $director;
    protected $lpo;
    protected $supplier;
    protected $received_by;
    protected $received_for;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Delivery $delivery, Lpo $lpo)
    {

        $this->delivery = $delivery;
        $this->lpo = $lpo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $ccs = [];
        $ccs[0] = $this->delivery->received_by->email;
        $this->view('emails/notify_delivery')         
        ->replyTo([
                'email' => Config::get('mail.reply_to')['address'],
            ])           
        ->cc($ccs);

        return $this->to($this->delivery->received_for->email)
                    ->with([
                            'delivery' => $this->delivery,
                            'lpo' => $this->lpo,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Delivery Received ".$this->delivery->external_ref);
    }
}
