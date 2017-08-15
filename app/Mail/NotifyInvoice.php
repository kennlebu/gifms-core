<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\InvoiceModels\Invoice;
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
        return $this->view('view.name');
    }
}
