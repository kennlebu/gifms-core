<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\StaffModels\Staff;
use Config;

class MobilePaymentInstructBank extends Mailable
{
    use Queueable, SerializesModels;

    protected $mobile_payment;
    protected $accountant;
    protected $financial_controller;
    protected $director;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MobilePayment $mobile_payment, $csv_attachment, $pdf_attachment)
    {
        $this->mobile_payment = $mobile_payment;
        $this->csv_attachment = $csv_attachment;
        $this->pdf_attachment = $pdf_attachment;
        $this->director2_id = 32;

        $this->accountant           = Staff::findOrFail(    (int)   Config::get('app.accountant_id'));
        $this->financial_controller = Staff::findOrFail(    (int)   Config::get('app.financial_controller_id'));
        $this->director             = Staff::findOrFail(    (int)   Config::get('app.director_id'));
        $this->director2 = Staff::findOrFail($this->director2_id);
        $this->requester = Staff::findOrFail($mobile_payment->requested_by_id);
        $this->bank_to = array('first_name'=>'Christine', 'last_name'=>'Kithembe', 'email'=>'Christine.Kithembe@nic-bank.com');
        $this->bank_cc = array(
            array('first_name'=>'Dennis', 'last_name'=>'Owino', 'email'=>'Dennis.Owino@nic-bank.com'),
            array('first_name'=>'Maureen', 'last_name'=>'Adega', 'email'=>'Maureen.Adega@nic-bank.com'),
            array('first_name'=>'NIC', 'last_name'=>'Bank', 'email'=>'niconline@nic-bank.com'),
            array('first_name'=>'Leonard', 'last_name'=>'Kerika', 'email'=>'Leonard.Kerika@nic-bank.com')
        );
        $this->chai_cc = array(
            array('first_name'=>'Jane', 'last_name'=>'Ayuma', 'email'=>'jayuma@clintonhealthaccess.org'),
            array('first_name'=>'Ramadhan', 'last_name'=>'Wangatia', 'email'=>'rwangatia@clintonhealthaccess.org'),
            array('first_name'=>'Davis', 'last_name'=>'Karambi', 'email'=>'dkarambi@clintonhealthaccess.org'),
            array('first_name'=>'Jackson', 'last_name'=>'Hungu', 'email'=>'jhungu@clintonhealthaccess.org'),
            array('first_name'=>'Rosemary', 'last_name'=>'Kihoto', 'email'=>'rkihoto@clintonhealthaccess.org'),
            array('first_name'=>'Gerald', 'last_name'=>'Macharia', 'email'=>'gmacharia@clintonhealthaccess.org')
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ccs = [];
        foreach($this->bank_cc as $bank_cc){
            array_push($ccs, $bank_cc['email']);
        }
        foreach($this->chai_cc as $chai_cc){
            array_push($ccs, $chai_cc['email']);
        }
        array_push($ccs, $this->requester->email);

        $subject = "Bulk MPESA Payment - ".$this->pad_zeros(5,$this->mobile_payment->id);


        $this->view('emails/mobile_payment_instruct_bank')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address'],

                ])           
            ->cc($ccs)
            ->attachData($this->pdf_attachment, 'ALLOWANCES_'.$this->pad_zeros(5,$this->mobile_payment->id).'.pdf')
            ->attachData($this->csv_attachment, 'ALLOWANCES_'.$this->pad_zeros(5,$this->mobile_payment->id).'.csv');      

        return $this->to($this->bank_to['email'])
            ->with([
                    'mobile_payment' => $this->mobile_payment,
                    'addressed_to' => $this->accountant,
                    'bank_to' => $this->bank_to,
                    'js_url' => Config::get('app.js_url'),
                ])
            ->subject("Bulk MPESA Payment - ".$this->pad_zeros(5,$this->mobile_payment->id));
    }

    /**
     * Adds zeros at the beginning of string until the desired
     * length is reached.
     */
    public function pad_zeros($desired_length, $data){
        if(strlen($data)<$desired_length){
            return str_repeat('0', $desired_length-strlen($data)).$data;
        }
        else{
            return $data;
        }
    }
}
