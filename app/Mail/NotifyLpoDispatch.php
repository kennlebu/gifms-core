<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\LPOModels\Lpo;
use App\Models\StaffModels\Staff;
use App\Models\SuppliesModels\Supplier;
use Config;
use PDF;
use Illuminate\Support\Facades\File;

class NotifyLpoDispatch extends Mailable
{
    use Queueable, SerializesModels;

    protected $lpo;
    protected $accountant;
    protected $financial_controller;
    protected $director;
    protected $lpo_PM;
    protected $requester;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Lpo $lpo)
    {
        $this->lpo = $lpo;

        $this->accountant           = Staff::findOrFail(    (int)   Config::get('app.accountant_id'));
        $this->financial_controller = Staff::findOrFail(    (int)   Config::get('app.financial_controller_id'));
        $this->director             = Staff::findOrFail(    (int)   Config::get('app.director_id'));
        $this->requester = Staff::findOrFail($lpo->requested_by_id);
        $this->lpo_PM = Staff::findOrFail($lpo->project_manager_id);
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
            $supplier_to = array('name'=>$this->lpo->preffered_quotation->supplier->supplier_name,
                'email'=>$this->lpo->preffered_quotation->supplier->email);
            // CC second supplier email if it exists
            if(!empty($this->lpo->preffered_quotation->supplier->contact_email_1)){
                $supplier_cc = array('name'=>$this->lpo->preffered_quotation->supplier->supplier_name,
                'email'=>$this->lpo->preffered_quotation->supplier->contact_email_1);
            }
        }
        elseif(!empty($this->lpo->lpo_type)&&$this->lpo->lpo_type=='prenegotiated'){
            $supplier_to = array('name'=>$this->supplier->supplier_name,
                'email'=>$this->lpo->supplier->email);
            // CC second supplier email if it exists
            if(!empty($this->lpo->supplier->contact_email_1)){
                $supplier_cc = array('name'=>$this->lpo->supplier->supplier_name,
                'email'=>$this->lpo->supplier->contact_email_1);
            }
        }
        else {
            $supplier_to = array('name'=>$this->lpo->preffered_quotation->supplier->supplier_name,
                'email'=>$this->lpo->preffered_quotation->supplier->email);
            // CC second supplier email if it exists
            if(!empty($this->lpo->preffered_quotation->supplier->contact_email_1)){
                $supplier_cc = array('name'=>$this->lpo->preffered_quotation->supplier->supplier_name,
                'email'=>$this->lpo->preffered_quotation->supplier->contact_email_1);
            }
        }

        // CHAI cc
        $chai_cc = array(
            array('first_name'=>$this->financial_controller->f_name, 'last_name'=>$this->financial_controller->l_name, 'email'=>$this->financial_controller->email),
            array('first_name'=>$this->accountant->f_name, 'last_name'=>$this->accountant->l_name, 'email'=>$this->accountant->email),
            array('first_name'=>$this->director->f_name, 'last_name'=>$this->director->l_name, 'email'=>$this->director->email),
            array('first_name'=>$this->requester->f_name, 'last_name'=>$this->requester->l_name, 'email'=>$this->requester->email),
            array('first_name'=>$this->lpo_PM->f_name, 'last_name'=>$this->lpo_PM->l_name, 'email'=>$this->lpo_PM->email)
        );

        // Add Admin Manager to cc
        $admin_manager = Staff::whereHas('roles', function($query){
            $query->where('role_id', 10);  
        })->get();
        foreach($admin_manager as $am){
            array_push($chai_cc, array('first_name'=>$am->f_name, 'last_name'=>$am->l_name, 'email'=>$am->email));
        }

        $ccs = [];
        if(!empty($supplier_cc)){
            array_push($ccs, $supplier_cc['email']);
        }
        foreach($chai_cc as $chai_cc){
            array_push($ccs, $chai_cc['email']);
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

        $pdf_data = array('lpo'=>$this->lpo, 'vat' => $vat, 'subtotal' => $subtotal, 'director'=>$this->director);
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
        // return $this->to('kennlebu@live.com') // for test
            ->with([
                    'lpo' => $this->lpo,
                    'lpo_no' => $lpo_no,
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
}
