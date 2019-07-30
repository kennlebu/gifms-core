<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\StaffModels\Staff;
use App\Models\StaffModels\User;
use Config;

class MobilePaymentInstructBank extends Mailable
{
    use Queueable, SerializesModels;

    protected $mobile_payment;
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
        
        $this->requester = Staff::findOrFail($mobile_payment->requested_by_id);
        $this->PM = Staff::findOrFail($mobile_payment->project_manager_id);

        $this->bank_to = array('first_name'=>'Christine', 'last_name'=>'Kithembe', 'email'=>'Christine.Kithembe@nic-bank.com');
        $this->bank_cc = array(
            array('first_name'=>'Dennis', 'last_name'=>'Owino', 'email'=>'Dennis.Owino@nic-bank.com'),
            array('first_name'=>'Maureen', 'last_name'=>'Adega', 'email'=>'Maureen.Adega@nic-bank.com'),
            array('first_name'=>'NIC', 'last_name'=>'Bank', 'email'=>'niconline@nic-bank.com'),
            array('first_name'=>'Leonard', 'last_name'=>'Kerika', 'email'=>'Leonard.Kerika@nic-bank.com')
        );
        $this->chai_cc = array(
            array('first_name'=>$this->requester->f_name, 'last_name'=>$this->requester->l_name, 'email'=>$this->requester->email),
            array('first_name'=>$this->PM->f_name, 'last_name'=>$this->PM->l_name, 'email'=>$this->PM->email)
        );

        // Add financial controllers to cc
        $fm = User::withRole('financial-controller')->get();
        foreach($fm as $f){
            $chai_cc[] = array('first_name'=>$f->f_name, 'last_name'=>$f->l_name, 'email'=>$f->email);
        }
        // Add Accountants to cc
        $accountant = User::withRole('accountant')->get();
        foreach($accountant as $am){
            $chai_cc[] = array('first_name'=>$am->f_name, 'last_name'=>$am->l_name, 'email'=>$am->email);
        }
        // Add directors to cc
        $director = User::withRole('director')->get();
        foreach($director as $am){
            $chai_cc[] = array('first_name'=>$am->f_name, 'last_name'=>$am->l_name, 'email'=>$am->email);
        }
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
            $this->fputcsv_eol($fp, $line, "\r\n");
        }
        rewind($fp); // Place stream pointer at beginning
        $csv_file = stream_get_contents($fp);

        /* Generate PDF */
        // $pdf = PDF::loadView('pdf/mobile_payment_bank_instruction', $this->pdf_data);
        // $pdf_file = $pdf->stream();

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
                    'email' => Config::get('mail.reply_to')['address']
                ])           
            ->cc($ccs)
            // ->attachData($pdf_file, 'ALLOWANCES_'.$this->pdf_data['our_ref'].'.pdf')
            ->attachData($csv_file, 'ALLOWANCES_.csv');      

        return $this->to($this->bank_to['email'])
        // return $this->to('dkarambi@clintonhealthaccess.org') // for test
            ->with([
                    'mobile_payment' => $this->mobile_payment,
                    'addressed_to' => $this->accountant,
                    'bank_to' => $this->bank_to,
                    'js_url' => Config::get('app.js_url'),
                ])
            ->subject("Bulk MPESA Payment");
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


    // Writes an array to an open CSV file with a custom end of line.
    //
    // $fp: a seekable file pointer. Most file pointers are seekable, 
    //   but some are not. example: fopen('php://output', 'w') is not seekable.
    // $eol: probably one of "\r\n", "\n", or for super old macs: "\r"
    function fputcsv_eol($fp, $array, $eol) {
    fputcsv($fp, $array);
    if("\n" != $eol && 0 === fseek($fp, -1, SEEK_CUR)) {
        fwrite($fp, $eol);
    }
    }
}
