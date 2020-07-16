<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\LPOModels\Lpo;
use App\Models\StaffModels\Staff;
use Config;

class LpoInstructSupplier extends Mailable
{
    use Queueable, SerializesModels;

    protected $lpo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Lpo $lpo)
    {
        $this->lpo = $lpo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ccs = [] ;
        $ccs[] = $this->lpo->requested_by;

        // Add financial controllers to cc
        $fm = Staff::whereHas('roles', function($query){
            $query->where('role_id', 5);  
        })->get();
        foreach($fm as $f){
            $ccs[] = array('first_name'=>$f->f_name, 'last_name'=>$f->l_name, 'email'=>$f->email);
        }

        // Add Accountants to cc
        $accountant = Staff::whereHas('roles', function($query){
            $query->where('role_id', 8);  
        })->get();
        foreach($accountant as $am){
            $ccs[] = array('first_name'=>$am->f_name, 'last_name'=>$am->l_name, 'email'=>$am->email);
        }
        // Add directors to cc
        $director = Staff::whereHas('roles', function($query){
            $query->whereIn('role_id', [3,4]);  
        })->get();
        foreach($director as $am){
            $ccs[] = array('first_name'=>$am->f_name, 'last_name'=>$am->l_name, 'email'=>$am->email);
        }


        $this->view('emails/lpo_instruct_supplier')         
            ->replyTo([
                    'email' => Config::get('mail.reply_to')['address']
                ])               
            ->cc($ccs);

        if($this->lpo->status_id == 13){

            $accountant = Staff::whereHas('roles', function($query){
                $query->where('role_id', 8);  
            })->get();
            return $this->to($accountant)
                    ->with([
                            'lpo' => $this->lpo,
                            // 'addressed_to' => $this->accountant,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Approval Request ".$this->lpo->ref);
        }else if($this->lpo->status_id == 3){

            return $this->to($this->lpo->project_manager)
                    ->with([
                            'lpo' => $this->lpo,
                            'addressed_to' => $this->lpo->project_manager,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Approval Request ".$this->lpo->ref);
        }else if($this->lpo->status_id == 4){

            $fm = Staff::whereHas('roles', function($query){
                $query->where('role_id', 5);  
            })->get();
            return $this->to($fm)
                    ->with([
                            'lpo' => $this->lpo,
                            // 'addressed_to' => $this->financial_controller,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Approval Request ".$this->lpo->ref);
        }else if($this->lpo->status_id == 5){

            $director = Staff::whereHas('roles', function($query){
                $query->whereIn('role_id', [3,4]);  
            })->get();
            return $this->to($director)
                    ->with([
                            'lpo' => $this->lpo,
                            // 'addressed_to' => $this->director,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Approval Request ".$this->lpo->ref);
        }

        else if($this->lpo->status_id == 11){

            return $this->to($this->lpo->requested_by)
                    ->with([
                            'lpo' => $this->lpo,
                            'addressed_to' => $this->lpo->requested_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Cancelled ".$this->lpo->ref);
        }else if($this->lpo->status_id == 12){

            return $this->to($this->lpo->requested_by)
                    ->with([
                            'lpo' => $this->lpo,
                            'addressed_to' => $this->lpo->requested_by,
                            'js_url' => Config::get('app.js_url'),
                        ])
                    ->subject("LPO Rejected ".$this->lpo->ref);
        }
    }
}
