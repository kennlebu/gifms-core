<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\LPOModels\Lpo;
use App\Models\StaffModels\Staff;
use Config;
use PDF;

class NotifyLpoDispatch extends Mailable
{
    use Queueable, SerializesModels;

    protected $lpo;
    protected $lpo_PM;
    protected $requester;
    protected $m_director;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Lpo $lpo)
    {
        $this->lpo = $lpo;
        $this->lpo_PM = Staff::findOrFail($lpo->project_manager_id);
        $this->m_director = Staff::whereHas('roles', function($query){
                                $query->whereIn('role_id', [3]);  
                            })->where('official_post', 'Deputy Country Director')->first();

        if($lpo->requisitioned_by_id){
            $this->requester = Staff::findOrFail($lpo->requisitioned_by_id);
        }
        else{
            $this->requester = Staff::findOrFail($lpo->requested_by_id);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $supplier_cc = [];
        if(empty($this->lpo->lpo_type)||$this->lpo->lpo_type!='prenegotiated'){
            $supplier_to = ['name'=>$this->lpo->preffered_quotation->supplier->supplier_name,
                'email'=>$this->lpo->preffered_quotation->supplier->email];
            // CC second supplier email if it exists
            if(!empty($this->lpo->preffered_quotation->supplier->contact_email_1)){
                $supplier_cc[] = ['name'=>$this->lpo->preffered_quotation->supplier->contact_name_1,
                'email'=>$this->lpo->preffered_quotation->supplier->contact_email_1];
            }
            if(!empty($this->lpo->preffered_quotation->supplier->contact_email_2)){
                $supplier_cc[] = ['name'=>$this->lpo->preffered_quotation->supplier->contact_name_2,
                'email'=>$this->lpo->preffered_quotation->supplier->contact_email_2];
            }
        }
        elseif(!empty($this->lpo->lpo_type)&&$this->lpo->lpo_type=='prenegotiated'){
            $supplier_to = ['name'=>$this->supplier->supplier_name,
                'email'=>$this->lpo->supplier->email];
            // CC second supplier email if it exists
            if(!empty($this->lpo->supplier->contact_email_1)){
                $supplier_cc[] = ['name'=>$this->lpo->supplier->contact_name_1,
                'email'=>$this->lpo->supplier->contact_email_1];
            }
            if(!empty($this->lpo->supplier->contact_email_2)){
                $supplier_cc[] = ['name'=>$this->lpo->supplier->contact_name_2,
                'email'=>$this->lpo->supplier->contact_email_2];
            }
        }
        else {
            $supplier_to = ['name'=>$this->lpo->preffered_quotation->supplier->supplier_name,
                'email'=>$this->lpo->preffered_quotation->supplier->email];
            // CC second supplier email if it exists
            if(!empty($this->lpo->preffered_quotation->supplier->contact_email_1)){
                $supplier_cc[] = ['name'=>$this->lpo->preffered_quotation->supplier->contact_name_1,
                'email'=>$this->lpo->preffered_quotation->supplier->contact_email_1];
            }
            if(!empty($this->lpo->preffered_quotation->supplier->contact_email_2)){
                $supplier_cc[] = ['name'=>$this->lpo->preffered_quotation->supplier->contact_name_2,
                'email'=>$this->lpo->preffered_quotation->supplier->contact_email_2];
            }
        }

        // CHAI cc
        $chai_cc = [
            ['first_name'=>$this->requester->f_name, 'last_name'=>$this->requester->l_name, 'email'=>$this->requester->email],
            ['first_name'=>$this->lpo_PM->f_name, 'last_name'=>$this->lpo_PM->l_name, 'email'=>$this->lpo_PM->email]
        ];

        // Add financial controllers to cc
        $fm = Staff::whereHas('roles', function($query){
            $query->where('role_id', 5);  
        })->get();
        foreach($fm as $f){
            $chai_cc[] = ['first_name'=>$f->f_name, 'last_name'=>$f->l_name, 'email'=>$f->email];
        }

        // Add Accountants to cc
        $accountant = Staff::whereHas('roles', function($query){
            $query->where('role_id', 8);  
        })->get();
        foreach($accountant as $am){
            $chai_cc[] = ['first_name'=>$am->f_name, 'last_name'=>$am->l_name, 'email'=>$am->email];
        }
        // Add directors to cc
        $director = Staff::whereHas('roles', function($query){
            $query->whereIn('role_id', [3,4]);  
        })->get();
        foreach($director as $am){
            $chai_cc[] = ['first_name'=>$am->f_name, 'last_name'=>$am->l_name, 'email'=>$am->email];
        }

        // Add Admin Manager to cc
        $admin_manager = Staff::whereHas('roles', function($query){
            $query->where('role_id', 10);  
        })->get();
        foreach($admin_manager as $am){
            $chai_cc[] = ['first_name'=>$am->f_name, 'last_name'=>$am->l_name, 'email'=>$am->email];
        }

        $ccs = [];
        foreach($supplier_cc as $sup_cc){
            $ccs[] = $sup_cc['email'];
        }
        foreach($chai_cc as $chai_cc){
            $ccs[] = $chai_cc['email'];
        }

        $lpo_no = $this->pad_zeros(5,$this->lpo->id);

        /* Generate PDF */
        $subtotals = [];
        $vats = [];
        foreach($this->lpo->items as $item){
            array_push($subtotals,$item->calculated_sub_total);
            array_push($vats, $item->calculated_vat);
        }
        $subtotal = array_sum($subtotals);
        $vat = array_sum($vats);

        $pdf_data = array('lpo'=>$this->lpo, 'vat' => $vat, 'subtotal' => $subtotal, 'director' => $this->m_director);
        $pdf = PDF::loadView('pdf/notify_lpo_dispatch', $pdf_data);
        $pdf_file = $pdf->stream();

        if(empty($this->lpo->lpo_type)||$this->lpo->lpo_type!='prenegotiated')
        $subject = "LPO ".$lpo_no." ".$this->lpo->preffered_quotation->supplier->supplier_name;
        elseif(!empty($this->lpo->lpo_type)&&$this->lpo->lpo_type=='prenegotiated')
        $subject = "LPO ".$lpo_no." ".$this->lpo->supplier->supplier_name;
        else
        $subject = "LPO ".$lpo_no." ".$this->lpo->preffered_quotation->supplier->supplier_name;

        $this->view('emails/notify_lpo_dispatch')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address']
                ])           
            ->cc($ccs);
        if(empty($this->lpo->lpo_type)||$this->lpo->lpo_type!='prenegotiated')
        $this->attachData($pdf_file, 'LPO_'.$lpo_no.'_'.$this->lpo->preffered_quotation->supplier->supplier_name.'.pdf');  
        elseif(!empty($this->lpo->lpo_type)&&$this->lpo->lpo_type=='prenegotiated')
        $this->attachData($pdf_file, 'LPO_'.$lpo_no.'_'.$this->lpo->supplier->supplier_name.'.pdf');  
        else
        $this->attachData($pdf_file, 'LPO_'.$lpo_no.'_'.$this->lpo->preffered_quotation->supplier->supplier_name.'.pdf');   

        return $this->to($supplier_to['email'])
            ->with([
                    'lpo' => $this->lpo,
                    'lpo_no' => $lpo_no,
                    'js_url' => Config::get('app.js_url')
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
}
