<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;
use Config;

class NotifyPayment extends Mailable
{
    use Queueable, SerializesModels;

    protected $payable;
    protected $payable_type;
    protected $payment;
    protected $amount;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payable, $payment, $amount, $type=null)
    {
        if(empty($type)){
            $this->payable_type = $payment->payable_type;
        }
        else {
            $this->payable_type = $payment;
        }
        $this->payable = $payable;    
        $this->payment = $payment;
        $this->amount = $amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $ccs = [] ;

        // Add accountants and financial-reviewers to cc
        $ccs = Staff::whereHas('roles', function($query){
            $query->whereIn('role_id', [8,13]);  
        })->get();

        $this->view('emails.generic')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],
                ]);

        
        $last_paragraph = "Should you have any questions, or queries on the above, please do not hesitate to get in touch with us via:".
        "<br/><br/>
        3rd flr, Timau Plaza, Arwings Kodhek Road,<br/>
        P O Box 2011-00100 Nairobi, Kenya<br/>
        (t) : 254 20 514 3100/5<br/>
        (e) : <a href='mailto:jayuma@clintonhealthaccess.org'>jayuma@clintonhealthaccess.org</a><br/>
        <a href='https://www.clintonhealthaccess.org'>www.clintonhealthaccess.org</a>";

        $signature = "Best regards,<br/><br/> <em>Clinton Health Access Initiative (Kenya) - Finance Team</em>";

        if($this->payable_type == 'advances'){

            $title = "Payment for Mobile Payment ".$this->payable->ref;
            $paragraphs = [];
            $paragraphs[] = 'To Whom It May Concern,';
            $paragraphs[] = 'A payment of amount '. ($this->payable->currency->currency_name ?? '') .' '. (empty($this->amount) ? $this->payment->net_amount : $this->amount) .' has been made for your advance Ref. '.$this->payable->ref.' - '.$this->payable->expense_desc;
            $paragraphs[] = $last_paragraph;

            return $this->to($this->payable->requested_by->email)
                    ->with([
                        'title' => $title,
                        'paragraphs' => $paragraphs,
                        'signature' => $signature
                    ])
                    ->cc($ccs)
                    ->subject("Payment for advance ".$this->payable->ref);

        }
        else if($this->payable_type == 'claims'){

            $title = "Payment for claim ".$this->payable->ref;
            $paragraphs = [];
            $paragraphs[] = 'To Whom It May Concern, <br/>'. $this->payment->paid_to_name;
            $paragraphs[] = 'A payment of amount '. ($this->payable->currency->currency_name ?? '') .' '. (empty($this->amount) ? $this->payment->net_amount : $this->amount) .' has been made for your claim Ref. '.$this->payable->ref.' - '.$this->payable->expense_desc;
            $paragraphs[] = $last_paragraph;

            return $this->to($this->payable->requested_by->email)
                    ->with([
                        'title' => $title,
                        'paragraphs' => $paragraphs,
                        'signature' => $signature
                    ])
                    ->subject("Payment for claim ".$this->payable->ref);

        }
        else if($this->payable_type == 'mobile_payments'){
            $title = "Payment for Mobile Payment ".$this->payable->ref;
            $paragraphs = [];
            $paragraphs[] = 'To Whom It May Concern,';
            $paragraphs[] = 'A payment of amount '. ($this->payable->currency->currency_name ?? '') .' '. (empty($this->amount) ? $this->payment->net_amount : $this->amount) .' has been made for your mobile payment Ref. '.$this->payable->ref.' - '.$this->payable->expense_desc;
            $paragraphs[] = $last_paragraph;

            return $this->to($this->payable->requested_by->email)
                    ->with([
                        'title' => $title,
                        'paragraphs' => $paragraphs,
                        'signature' => $signature
                    ])
                    ->subject("Payment for mobile payment ".$this->payable->ref);

        }
        else if($this->payable_type == 'invoices'){

            $title = "Payment for invoice ".$this->payable->external_ref;
            $paragraphs = [];

            $paragraphs[] = 'To Whom It May Concern, <br/>'. $this->payment->paid_to_name;
            $paragraphs[] = 'A payment of amount '. ($this->payable->currency->currency_name ?? '') .' '. (empty($this->amount) ? $this->payment->net_amount : $this->amount) .' has been made for your invoice Ref. '.$this->payable->external_ref.' - '.$this->payable->expense_desc;

            $taxes = '';
            if (!empty($this->payable->withholding_tax) && !empty($this->payable->withholding_vat)) {
                $taxes = 'This payment has been subject to VAT and withholding Income Tax.';
            }
            elseif (!empty($this->payable->withholding_tax)) {
                $taxes = 'This payment has been subject to withholding Income Tax.';
            }
            elseif (!empty($this->payable->withholding_vat)) {
                $taxes = 'This payment has been subject to VAT.';
            }

            if(!empty($taxes)) {
                $paragraphs[] = $taxes;
            }

            $paragraphs[] = $last_paragraph;
            

            return $this->to($this->payable->supplier->email)
                    ->with([
                        'title' => $title,
                        'paragraphs' => $paragraphs,
                        'signature' => $signature
                    ])
                    ->subject("Payment for invoice ".$this->payable->external_ref);
        }

    }
}
