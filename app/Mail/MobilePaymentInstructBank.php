<?php

namespace App\Mail;

use App\Models\BankingModels\BankContact;
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
        
        $this->requester = Staff::findOrFail($mobile_payment->requested_by_id);
        $this->PM = Staff::findOrFail($mobile_payment->project_manager_id);

        $bank_defaults = BankContact::all();
        foreach($bank_defaults as $default){
            $names = explode(' ', $default->name);
            $fname = array_shift($names);
            $onames = count($names) > 0 ? implode(' ', $names) : '';
            
            if($default->default_bank_contact == '1'){
                $this->bank_to[] = ['first_name'=>$fname, 'last_name'=>$onames, 'email'=>$default->email];
            }
            else {
                $this->bank_cc[] = ['first_name'=>$fname, 'last_name'=>$onames, 'email'=>$default->email];
            }
        }

        // $bank_others = BankContact::where('default_bank_contact', '!=', '1')->get();
        // foreach($bank_others as $other){
        //     $names = explode(' ', $other);
        //     $fname = array_shift($names);
        //     $onames = count($names) > 0 ? implode(' ', $names) : '';
            
        //     $this->bank_cc = ['first_name'=>$fname, 'last_name'=>$onames, 'email'=>$other->email];
        // }

        // $this->bank_to = array('first_name'=>'Christine', 'last_name'=>'Kithembe', 'email'=>'Christine.Kithembe@nic-bank.com');
        // $this->bank_cc = array(
        //     array('first_name'=>'Dennis', 'last_name'=>'Owino', 'email'=>'Dennis.Owino@nic-bank.com'),
        //     array('first_name'=>'Maureen', 'last_name'=>'Adega', 'email'=>'Maureen.Adega@nic-bank.com'),
        //     array('first_name'=>'NIC', 'last_name'=>'Bank', 'email'=>'niconline@nic-bank.com'),
        //     array('first_name'=>'Leonard', 'last_name'=>'Kerika', 'email'=>'Leonard.Kerika@nic-bank.com')
        // );
        $this->chai_cc = array(
            array('first_name'=>$this->requester->f_name, 'last_name'=>$this->requester->l_name, 'email'=>$this->requester->email),
            array('first_name'=>$this->PM->f_name, 'last_name'=>$this->PM->l_name, 'email'=>$this->PM->email)
        );

        // Add financial controllers to cc
        $fm = Staff::whereHas('roles', function($query){
            $query->where('role_id', 5);  
        })->get();
        foreach($fm as $f){
            $this->chai_cc[] = array('first_name'=>$f->f_name, 'last_name'=>$f->l_name, 'email'=>$f->email);
        }
        // Add Accountants to cc
        $accountant = Staff::whereHas('roles', function($query){
            $query->where('role_id', 8);  
        })->get();
        foreach($accountant as $am){
            $this->chai_cc[] = array('first_name'=>$am->f_name, 'last_name'=>$am->l_name, 'email'=>$am->email);
        }
        // Add directors to cc
        $director = Staff::whereHas('roles', function($query){
            $query->whereIn('role_id', [3,4]);  
        })->get();
        foreach($director as $am){
            $this->chai_cc[] = array('first_name'=>$am->f_name, 'last_name'=>$am->l_name, 'email'=>$am->email);
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

        $ccs = [];
        $to = [];
        foreach($this->bank_to as $bank_to){
            $to[] = $bank_to['email'];
        }
        foreach($this->bank_cc as $bank_cc){
            $ccs[] = $bank_cc['email'];
        }
        foreach($this->chai_cc as $chai_cc){
            $ccs[] = $chai_cc['email'];
        }
        $ccs[] = $this->requester->email;

        $subject = "Bulk MPESA Payment - ".$this->mobile_payment->ref;

        $this->view('emails/mobile_payment_instruct_bank')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address']
                ])           
            ->cc($ccs)
            ->attachData($csv_file, 'ALLOWANCES_'.str_replace('/','_',$this->mobile_payment->ref).'.csv');      

        return $this->to($to)
            ->with([
                    'mobile_payment' => $this->mobile_payment,
                    'bank_to' => $this->bank_to,
                    'js_url' => Config::get('app.js_url'),
                ])
            ->subject($subject);
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
    // but some are not. example: fopen('php://output', 'w') is not seekable.
    // $eol: probably one of "\r\n", "\n", or for super old macs: "\r"
    function fputcsv_eol($fp, $array, $eol) {
        fputcsv($fp, $array);
        if("\n" != $eol && 0 === fseek($fp, -1, SEEK_CUR)) {
            fwrite($fp, $eol);
        }
    }
}
