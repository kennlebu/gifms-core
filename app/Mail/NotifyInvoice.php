<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\InvoicesModels\Invoice;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;
use Config;

class NotifyInvoice extends Mailable
{
    use Queueable, SerializesModels;

    protected $invoice;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {

        $this->invoice   = Invoice::with( 
                                    'raised_by',
                                    'received_by',
                                    'raise_action_by',
                                    'status',
                                    'project_manager',
                                    'supplier',
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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ccs = [] ;

        $this->view('emails/notify_invoice')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address']
                ]);

        if($this->invoice->status_id == 11){
            
            return $this->to($this->invoice->raised_by)
                    ->with([
                            'invoice' => $this->invoice,
                            'addressed_to' => $this->invoice->raised_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    
                    ->subject("Invoice Received/Logged  ".$this->invoice->external_ref);
        }else if($this->invoice->status_id == 12){
            
            $ccs[] = $this->invoice->raised_by;
            $to = User::withRole('accountant')->get();

            return $this->to($to)
                    ->with([
                            'invoice' => $this->invoice,
                            // 'addressed_to' => $this->accountant,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->cc($ccs)
                    ->subject("Invoice Approval Request ".$this->invoice->external_ref);
        }else if($this->invoice->status_id == 1){

            return $this->to($this->invoice->project_manager)
                    ->with([
                            'invoice' => $this->invoice,
                            'addressed_to' => $this->invoice->project_manager,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Invoice Approval Request ".$this->invoice->external_ref);
        }else if($this->invoice->status_id == 2){

            $to = User::withRole('financial-controller')->get();
            return $this->to($to)
                    ->with([
                            'invoice' => $this->invoice,
                            // 'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Invoice Approval Request ".$this->invoice->external_ref);
        }else if($this->invoice->status_id == 3){

            $to = User::withRole('director')->get();
            return $this->to($to)
                    ->with([
                            'invoice' => $this->invoice,
                            // 'addressed_to' => $this->director,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Invoice Approval Request ".$this->invoice->external_ref);
        }else if($this->invoice->status_id == 4){

            $to = User::withRole('financial-controller')->get();
            return $this->to($to)
                    ->with([
                            'invoice' => $this->invoice,
                            // 'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Invoice Approval Request ".$this->invoice->external_ref);
        }

        else if($this->invoice->status_id == 9){

            return $this->to($this->invoice->raised_by)
                    ->with([
                            'invoice' => $this->invoice,
                            'addressed_to' => $this->invoice->raised_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("Invoice Rejected ".$this->invoice->external_ref);
        }

    }
}
