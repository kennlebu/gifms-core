<?php

namespace App\Http\Controllers;

use App\Models\BankingModels\BankProjectBalances;
use App\Models\ClaimsModels\Claim;
use App\Models\FinanceModels\ExchangeRate;
use App\Models\FundsRequestModels\FundsRequest;
use App\Models\InvoicesModels\Invoice;
use App\Models\LeaveManagementModels\LeaveRequest;
use App\Models\LPOModels\Lpo;
use App\Models\MobilePaymentModels\MobilePayment;
use App\Models\OtherModels\ActivityNotification;
use App\Models\PaymentModels\Payment;
use App\Models\PaymentModels\PaymentBatch;
use App\Models\Requisitions\Requisition;
use DateTime;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function getMetrics(){
        $metrics = [];

        $metrics[] = $this->getBankBalance();
        $metrics[] = $this->getUnapproved();

        return response()->json($metrics, 200,array(),JSON_PRETTY_PRINT);
    }


    public function getRecentActivity(){
        $user = $this->current_user();
        $activity = ActivityNotification::with('action_by', 'user')->where('user_id', $user->id)->orWhere('action_by_id', $user->id);

        // Show all the relevant activity to the admins
        if($user->hasRole([ 'super-admin', 'admin', 'director', 'associate-director', 'financial-controller', 'accountant', 'assistant-accountant'])){
            $activity = $activity->orWhere('show_admins', 'true');
        }

        $activity = $activity->get();


        return response()->json($activity, 200,array(),JSON_PRETTY_PRINT);
    }

    private function getBankBalance(){
        // Get bank balance projections
        $unpaid_total = 0;
        $paid_total = 0;
        $end_date = new DateTime(date('Y-m-d'));
        $end_date = $end_date->format('Y-m-t')." 23:59:59";
        $start_date = date('Y-m').'-01 00:00:00';

        $unpaid = Invoice::whereIn('status_id', [1,2,3,4,10,11,12])->get();
        $paid = [];
        $batches = PaymentBatch::whereBetween('created_at', [$start_date, $end_date])->pluck('id')->toArray();
        if(!empty($batches)){
            $invoice_ids = Payment::where('payable_type', 'invoices')->wherein('id', $batches)->pluck('payable_id')->toArray();
            if(!empty($invoice_ids)){
                $paid = Invoice::whereIn('id', $invoice_ids)->get();
            }            
        }

        // Unpaid invoices
        foreach($unpaid as $invoice){
            if($invoice->currency_id == 1) {
                $rate = ExchangeRate::whereMonth('active_date', date('m'))->orderBy('active_date', 'DESC')->first();
                if(!empty($rate)) $rate = $rate->exchange_rate;
                else $rate = 101.70;

                $unpaid_total += (float) ($invoice->total / $rate);
            }
            else $unpaid_total += (float) $invoice->total;
        }

        // Paid invoices
        foreach($paid as $paid_invoice){
            if($paid_invoice->currency_id == 1) {
                $rate = ExchangeRate::whereMonth('active_date', date('m'))->orderBy('active_date', 'DESC')->first();
                if(!empty($rate)) $rate = $rate->exchange_rate;
                else $rate = 101.70;

                $paid_total += (float) ($paid_invoice->total / $rate);
            }
            else $paid_total += (float) $paid_invoice->total;
        }
        
        $bank_balance = BankProjectBalances::whereMonth('balance_date', date('m'))->whereYear('balance_date', date('Y'))->first();
        
        if(empty($bank_balance) || $bank_balance->balance == 0){
            $current = 0 - $unpaid_total - $paid_total;
            return ['type'=>'bank_balance', 'title'=>'Bank balance estimate', 'total_balance'=>0, 'beginning_balance'=>0, 'accruals'=>0, 'unpaid'=>$unpaid_total, 'paid'=>$paid_total, 'action'=>'Add bank balance', 'current'=>$current];
        }
        else{
            $current = $bank_balance->total_balance ?? 0 - $bank_balance->accruals ?? 0 - $unpaid_total - $paid_total;
            return ['type'=>'bank_balance', 'id'=>$bank_balance->id, 'title'=>'Bank balance estimate', 'total_balance'=>$bank_balance->total_balance, 'beginning_balance'=>$bank_balance->balance, 'accruals'=>$bank_balance->accruals ?? 0, 'unpaid'=>$unpaid_total, 'paid'=>$paid_total, 'current'=>$current];
        }
    }

    private function getUnapproved(){
        $total = 0;
        $invoices = 0;
        $lpos = 0;
        $claims = 0;
        $mobile_payments = 0;
        $requisitions = 0;
        $others = 0;
        $user = $this->current_user();

        // Accountants
        if($user->hasRole(['accountant', 'assistant-accountant'])){
            $invoices += Invoice::where('status_id', 12)->count();
            $lpos += Lpo::where('status_id', 13)->count();
            $claims += Claim::where('status_id', 10)->count();
            $mobile_payments += MobilePayment::where('status_id', 9)->count();
        }

        // Finance
        if($user->hasRole(['financial-controller'])){
            $invoices += Invoice::where('status_id', 2)->count();
            $lpos += Lpo::where('status_id', 4)->count();
            $claims += Claim::where('status_id', 3)->count();
            $mobile_payments += MobilePayment::where('status_id', 3)->count();
            $others += FundsRequest::where('status_id', 2)->count();
        }

        // PMs
        if($user->hasRole(['program-manager'])){
            $invoices += Invoice::where('status_id', 1)->where('project_manager_id', $user->id)->count();
            $lpos += Lpo::where('status_id', 3)->where('project_manager_id', $user->id)->count();
            $claims += Claim::where('status_id', 2)->where('project_manager_id', $user->id)->count();
            $mobile_payments += MobilePayment::where('status_id', 2)->where('project_manager_id', $user->id)->count();
            $requisitions += Requisition::where('status_id', 2)->where('program_manager_id', $user->id)->count();
            $others += LeaveRequest::where('status_id', 2)->where('line_manager_id', $user->id)->count();
        }

        // Directors
        if($user->hasRole(['director', 'associate-director'])){
            $invoices += Invoice::where('status_id', 3)->count();
            $lpos += Lpo::where('status_id', 5)->count();
            $claims += Claim::where('status_id', 4)->count();
            $mobile_payments += MobilePayment::where('status_id', 8)->count();
            $others += LeaveRequest::where('status_id', 2)->where('line_manager_id', $user->id)->count();
        }
        
        $total += ($invoices + $lpos + $claims + $mobile_payments + $requisitions + $others);

        return ['type'=>'approvals', 'invoices'=>$invoices, 'lpos'=>$lpos, 'claims'=>$claims, 'mobile_payments'=>$mobile_payments, 'requisitions'=>$requisitions, 'others'=>$others, 'total'=>$total];
    }
}
