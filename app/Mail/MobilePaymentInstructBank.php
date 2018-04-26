<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\StaffModels\Staff;
use Config;
use PDF;
use Excel;
use Illuminate\Support\Facades\File;

class MobilePaymentInstructBank extends Mailable
{
    use Queueable, SerializesModels;

    protected $mobile_payment;
    protected $accountant;
    protected $financial_controller;
    protected $director;
    protected $csv_data;
    protected $pdf_data;
    protected $bank_cc;
    protected $chai_cc;
    protected $bank_to;
    protected $requester;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(MobilePayment $mobile_payment, $csv_data, $pdf_data)
    {
        $this->mobile_payment = $mobile_payment;
        $this->csv_data = $csv_data;
        $this->pdf_data = $pdf_data;
        $this->director2_id = 32;

        $this->accountant           = Staff::findOrFail(    (int)   Config::get('app.accountant_id'));
        $this->financial_controller = Staff::findOrFail(    (int)   Config::get('app.financial_controller_id'));
        $this->director             = Staff::findOrFail(    (int)   Config::get('app.director_id'));
        $this->director2 = Staff::findOrFail($this->director2_id);
        $this->requester = Staff::findOrFail($mobile_payment->requested_by_id);
        $this->PM = Staff::findOrFail($mobile_payment->project_manager_id);

        $this->bank_to = array('first_name'=>'Christine', 'last_name'=>'Kithembe', 'email'=>'Christine.Kithembe@nic-bank.com');
        // $this->bank_cc = array(
        //     array('first_name'=>'Dennis', 'last_name'=>'Owino', 'email'=>'Dennis.Owino@nic-bank.com'),
        //     array('first_name'=>'Maureen', 'last_name'=>'Adega', 'email'=>'Maureen.Adega@nic-bank.com'),
        //     array('first_name'=>'NIC', 'last_name'=>'Bank', 'email'=>'niconline@nic-bank.com'),
        //     array('first_name'=>'Leonard', 'last_name'=>'Kerika', 'email'=>'Leonard.Kerika@nic-bank.com')
        // );
        $this->chai_cc = array(
            array('first_name'=>$this->financial_controller->f_name, 'last_name'=>$this->financial_controller->l_name, 'email'=>$this->financial_controller->email),
            array('first_name'=>$this->accountant->f_name, 'last_name'=>$this->accountant->l_name, 'email'=>$this->accountant->email),
            array('first_name'=>$this->director->f_name, 'last_name'=>$this->director->l_name, 'email'=>$this->director->email),
            array('first_name'=>$this->director2->f_name, 'last_name'=>$this->director2->l_name, 'email'=>$this->director2->email),
            array('first_name'=>$this->requester->f_name, 'last_name'=>$this->requester->l_name, 'email'=>$this->requester->email),
            array('first_name'=>$this->PM->f_name, 'last_name'=>$this->PM->l_name, 'email'=>$this->PM->email),
            
            array('first_name'=>'Rosemary', 'last_name'=>'Kihoto', 'email'=>'rkihoto@clintonhealthaccess.org')
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
        /* Generate CSV */
        if (!$fp = fopen('php://temp', 'w+')) return false; // Open temp file pointer
        foreach ($this->csv_data as $line){
            fputcsv($fp, $line);
        }
        rewind($fp); // Place stream pointer at beginning
        $csv_file = stream_get_contents($fp);

        /* Generate PDF */
        $pdf = PDF::loadView('pdf/mobile_payment_bank_instruction', $this->pdf_data);
        $pdf_file = $pdf->stream();

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
            ->attachData($pdf_file, 'ALLOWANCES_'.$this->pad_zeros(5,$this->mobile_payment->id).'.pdf')
            ->attachData($csv_file, 'ALLOWANCES_'.$this->pad_zeros(5,$this->mobile_payment->id).'.csv');      

        // return $this->to($this->bank_to['email'])
        return $this->to('dkarambi@clintonhealthaccess.org') // for test
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
