<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\InvoicesModels\Invoice;
use App\Models\StaffModels\Staff;
use Config;

class NotifyInvoice extends Mailable
{
    use Queueable, SerializesModels;

    protected $invoice;
    protected $accountant;
    protected $financial_controller;
    protected $director;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {

        $this->invoice   = Invoice::with( 
                                    'raised_by',
                                    'raise_action_by',
                                    'status',
                                    'project_manager',
                                    'currency',
                                    'lpo',
                                    'rejected_by',
                                    'approvals',
                                    'allocations',
                                    'comments'
                                )->findOrFail($invoice->id);
        foreach ($this->invoice->approvals as $key => $value) {
            $this->invoice->approvals[$key]['approver'] = Staff::find($this->invoice->approvals[$key]['approver_id']);
        }


        $this->accountant           = Staff::findOrFail(    (int)   Config::get('app.accountant_id'));
        $this->financial_controller = Staff::findOrFail(    (int)   Config::get('app.financial_controller_id'));
        $this->director             = Staff::findOrFail(    (int)   Config::get('app.director_id'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $bccs = [] ;
        $bccs[0] = $this->accountant;
        $bccs[1] = $this->financial_controller;
        $bccs[2] = $this->director;


        $this->view('emails/notify_invoice')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ])           
            ->cc($this->invoice->raised_by)       
            ->bcc($bccs);











        if($this->invoice->status_id == 12){



            return $this->to($this->accountant)
                    ->with([
                            'invoice' => $this->invoice,
                            'addressed_to' => $this->accountant,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Invoice Approval Request ".$this->invoice->ref);
        }else if($this->invoice->status_id == 1){



            return $this->to($this->invoice->project_manager)
                    ->with([
                            'invoice' => $this->invoice,
                            'addressed_to' => $this->invoice->project_manager,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Invoice Approval Request ".$this->invoice->ref);
        }else if($this->invoice->status_id == 2){



            return $this->to($this->financial_controller)
                    ->with([
                            'invoice' => $this->invoice,
                            'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Invoice Approval Request ".$this->invoice->ref);
        }else if($this->invoice->status_id == 3){



            return $this->to($this->director)
                    ->with([
                            'invoice' => $this->invoice,
                            'addressed_to' => $this->director,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Invoice Approval Request ".$this->invoice->ref);
        }







        // else if($this->invoice->status_id == 11){



        //     return $this->to($this->invoice->raised_by)
        //             ->with([
        //                     'invoice' => $this->invoice,
        //                     'addressed_to' => $this->invoice->raised_by,
        //                     'js_url' => Config::get('app.js_url'),
        //                 ])
        //             ->subject("Invoice Cancelled ".$this->invoice->ref);
        // }
        else if($this->invoice->status_id == 9){



            return $this->to($this->invoice->raised_by)
                    ->with([
                            'invoice' => $this->invoice,
                            'addressed_to' => $this->invoice->raised_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Invoice Rejected ".$this->invoice->ref);
        }

    }
}
