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

        if($this->current_user()->hasRole(['accountant', 'assistant-accountant','financial-controller','director', 'associate-director', 'financial-reviewer'])){
            $metrics[] = $this->getBankBalance();
        }

        if($this->current_user()->can(['Batch Payments'])){
            $metrics[] = $this->getUnbatchedPayments();
        }
        
        if($this->current_user()->hasRole(['accountant','financial-controller','director', 'associate-director', 'financial-reviewer','program-manager'])){
            $metrics[] = $this->getUnapproved();
        }

        return response()->json($metrics, 200);
    }


    public function getRecentActivity(){
        $user = $this->current_user();
        $activity = ActivityNotification::with('action_by', 'user')->where('user_id', $user->id)->orWhere('action_by_id', $user->id);

        // Show all the relevant activity to the admins
        if($user->hasRole(['super-admin', 'admin', 'director', 'associate-director', 'financial-controller', 'accountant', 'assistant-accountant', 'admin-manager','financial-reviewer'])){
            $activity = $activity->orWhere('show_admins', true);
        }

        $activity = $activity->orderBy('created_at', 'DESC')->limit(15)->get();


        return response()->json($activity, 200);
    }

    private function getBankBalance(){
        // Get bank balance projections
        $unpaid_total = 0;
        $paid_total = 0;
        $end_date = new DateTime(date('Y-m-d'));
        $end_date = $end_date->format('Y-m-t')." 23:59:59";
        $start_date = date('Y-m').'-01 00:00:00';
        
        $rate = ExchangeRate::whereYear('active_date', date('Y', strtotime("first day of previous month")))->whereMonth('active_date', date('m', strtotime("first day of previous month")))->orderBy('active_date', 'DESC')->first();
        if(!empty($rate)) $rate = $rate->exchange_rate;
        else $rate = 'no_rate';

        // Only calculate the unpaid transactions if USD rate for current month
        // has been set.
        /* INVOICES */
        // Unpaid invoices
        if($rate != 'no_rate'){
            $unpaid_invoices = Invoice::whereIn('status_id', [1,2,3,4,10,11,12])->whereNotNull('raised_by_id')->where(function($query){
                $query->whereNull('archived')->orWhere('archived', '!=', 1);
            })->get();
            foreach($unpaid_invoices as $invoice){
                if($invoice->currency_id == 1) {
                    $unpaid_total += (float) ($invoice->total / $rate);
                }
                else $unpaid_total += (float) $invoice->total;
            }
    
            // Paid invoices
            $paid_invoices = [];
            $batches = PaymentBatch::whereBetween('created_at', [$start_date, $end_date])->pluck('id')->toArray();
            if(!empty($batches)){
                $invoice_ids = Payment::where('payable_type', 'invoices')->wherein('payment_batch_id', $batches)->pluck('payable_id')->toArray();
                if(!empty($invoice_ids)){
                    $paid_invoices = Invoice::whereIn('id', $invoice_ids)->get();
                }            
            }
            foreach($paid_invoices as $paid_invoice){
                if($paid_invoice->currency_id == 1) {
                    $paid_total += (float) ($paid_invoice->total / $rate);
                }
                else $paid_total += (float) $paid_invoice->total;
            }
    
            /* CLAIMS */
            // Unpaid claims
            $unpaid_claim = Claim::whereIn('status_id', [1,2,3,4,5,10])->where(function($query){
                $query->whereNull('archived')->orWhere('archived', '!=', 1);
            })->get();
            foreach($unpaid_claim as $claim){
                if($claim->currency_id == 1) {
                    $unpaid_total += (float) ($claim->total / $rate);
                }
                else $unpaid_total += (float) $claim->total;
            }
    
            // Paid claims
            $paid_claims = [];
            $batches = PaymentBatch::whereBetween('created_at', [$start_date, $end_date])->pluck('id')->toArray();
            if(!empty($batches)){
                $claim_ids = Payment::where('payable_type', 'claims')->wherein('payment_batch_id', $batches)->pluck('payable_id')->toArray();
                if(!empty($claims_ids)){
                    $paid_claims = Claim::whereIn('id', $claim_ids)->get();
                }            
            }
            foreach($paid_claims as $paid_claim){
                if($paid_claim->currency_id == 1) {
                    $paid_total += (float) ($paid_claim->total / $rate);
                }
                else $paid_total += (float) $paid_claim->total;
            }
    
            /* MOBILE PAYMENTS */
            // Unpaid mobile payments
            $unpaid_mp = MobilePayment::whereIn('status_id', [1,2,3,8,9,15,16])->where(function($query){
                $query->whereNull('archived')->orWhere('archived', '!=', 1);
            })->get();
            foreach($unpaid_mp as $mp){
                if($mp->currency_id == 1) {
                    $unpaid_total += (float) ($mp->totals / $rate);
                }
                else $unpaid_total += (float) $mp->totals;
            }
            
            // Paid mobile payments
            $paid_mps = MobilePayment::whereMonth('management_approval_at', '=', date('m'))->whereYear('management_approval_at', '=', date('Y'))->get();

            foreach($paid_mps as $paid_mp){
                if($paid_mp->currency_id == 1) {
                    $paid_total += (float) ($paid_mp->totals / $rate);
                }
                else $paid_total += (float) $paid_mp->totals;
            }
        }

        
        $bank_balance = BankProjectBalances::whereMonth('balance_date', date('m'))->whereYear('balance_date', date('Y'))->first();
        
        if(empty($bank_balance) || $bank_balance->balance == 0){
            if($rate == 'no_rate'){
                return ['type'=>'bank_balance', 'title'=>'Cash available', 'total_balance'=>0, 'beginning_balance'=>0, 'accruals'=>0, 'rate'=>$rate, 'action'=>'Add bank balance'];
            }
            $current = 0 - $paid_total - $unpaid_total;
            return ['type'=>'bank_balance', 'title'=>'Cash available', 'total_balance'=>0, 'beginning_balance'=>0, 'accruals'=>0, 'unpaid'=>$unpaid_total, 'paid'=>$paid_total, 'action'=>'Add bank balance', 'current'=>$current];
        }
        else{
            if($rate == 'no_rate'){
                return ['type'=>'bank_balance', 'id'=>$bank_balance->id, 'title'=>'Cash available', 'total_balance'=>$bank_balance->total_balance, 'beginning_balance'=>$bank_balance->balance, 'accruals'=>$bank_balance->accruals ?? 0, 'rate'=>$rate, 'cash_received'=>$bank_balance->total_cash_received];
            }
            $current = $bank_balance->total_balance - $paid_total - $unpaid_total - $bank_balance->accruals;
            return ['type'=>'bank_balance', 'id'=>$bank_balance->id, 'title'=>'Cash available', 'total_balance'=>$bank_balance->total_balance, 'beginning_balance'=>$bank_balance->balance, 'accruals'=>$bank_balance->accruals ?? 0, 'unpaid'=>$unpaid_total, 'paid'=>$paid_total, 'current'=>$current, 'cash_received'=>$bank_balance->total_cash_received];
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
        if($user->hasRole(['accountant'])){
            $invoices += Invoice::where('status_id', 12)->count();
            $lpos += Lpo::where('status_id', 13)->count();
            $claims += Claim::where('status_id', 10)->count();
            $mobile_payments += MobilePayment::where('status_id', 9)->count();
        }

        // FR
        if($user->hasRole(['financial-reviewer'])){
            $invoices += Invoice::where('status_id', 14)->count();
            $lpos += Lpo::where('status_id', 16)->count();
            $claims += Claim::where('status_id', 12)->count();
            $mobile_payments += MobilePayment::where('status_id', 18)->count();
            $others += FundsRequest::where('status_id', 2)->count();
        }

        // FM
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

    private function getUnbatchedPayments(){
        $payments = Payment::where('status_id',1)->get();
        $total_kes = 0;
        $total_usd = 0;
        $invoices = 0;
        $claims = 0;
        foreach($payments as $payment){
            if($payment->currency_id == 1){
                $total_kes += $payment->amount;
            }
            elseif($payment->currency_id == 2){
                $total_usd += $payment->amount;
            }

            if($payment->payable_type == 'invoices'){
                $invoices++;
            }
            elseif($payment->payable_type == 'claims'){
                $claims++;
            }
        }

        return ['type'=>'batches', 'total_kes'=>$total_kes, 'total_usd'=>$total_usd, 'number'=>count($payments), 'invoices'=>$invoices, 'claims'=>$claims];
    }

    public function getLastThreeMonthsSpend(Request $request){
        $start_date = new DateTime();
        if(!empty($request->date)){
            $start_date = new DateTime($request->date);
        }
        
        $d = clone $start_date;
        $first_date = $d->format('Y-m-d');
        $d = clone $start_date;
        $second_date = $d->modify('first day of -1 month')->format('Y-m-d');
        $d = clone $start_date;
        $third_date = $d->modify('first day of -2 months')->format('Y-m-d');

        $labels = [];
        $data = [];

        // 1 month
        $first_batch = PaymentBatch::whereMonth('created_at', date('m', strtotime($first_date)))
                        ->whereYear('created_at', date('Y', strtotime($first_date)))
                        ->pluck('id')->toArray();
        $first_batch_total = $this->getBatchAmount($first_batch, $first_date);
        $labels[] = date('F', strtotime($first_date));
        $data[] = $first_batch_total;

        // 2 months
        $batch = PaymentBatch::whereMonth('created_at', date('m', strtotime($second_date)))
                        ->whereYear('created_at', date('Y', strtotime($second_date)))
                        ->pluck('id')->toArray();
        $second_batch_total = $this->getBatchAmount($batch, $second_date);
        $labels[] = date('F', strtotime($second_date));
        $data[] = $second_batch_total;

        // 3 months
        $batch = PaymentBatch::whereMonth('created_at', date('m', strtotime($third_date)))
                        ->whereYear('created_at', date('Y', strtotime($third_date)))
                        ->pluck('id')->toArray();
        $third_batch_total = $this->getBatchAmount($batch, $third_date);
        $labels[] = date('F', strtotime($third_date));
        $data[] = $third_batch_total;

        return response()->json(['labels'=>$labels, 'data'=>$data], 200);
    }

    private function getBatchAmount($batch_ids, $date){
        $total = 0;
        $prev = new DateTime($date);
        $prev_month_date = $prev->modify('first day of previous month')->format('Y-m-d');
        $rate = ExchangeRate::whereYear('active_date', date('Y'))->whereMonth('active_date', date('m', strtotime($prev_month_date)))->orderBy('active_date', 'DESC')->first();
        if(!empty($rate)) $rate = $rate->exchange_rate;
        else $rate = 'no_rate';

        if($rate !== 'no_rate'){
            if(!empty($batch_ids)){
                $invoice_ids = Payment::where('payable_type', 'invoices')->wherein('payment_batch_id', $batch_ids)->pluck('payable_id')->toArray();
                if(!empty($invoice_ids)){
                    $paid_invoices = Invoice::whereIn('id', $invoice_ids)->get();
                    foreach($paid_invoices as $paid_invoice){
                        if($paid_invoice->currency_id == 1) {
                            $total += (float) ($paid_invoice->total / $rate);
                        }
                        else $total += (float) $paid_invoice->total;
                    }
                }
                
                $claim_ids = Payment::where('payable_type', 'claims')->wherein('payment_batch_id', $batch_ids)->pluck('payable_id')->toArray();
                if(!empty($claims_ids)){
                    $paid_claims = Claim::whereIn('id', $claim_ids)->get();
                    foreach($paid_claims as $paid_claim){
                        if($paid_claim->currency_id == 1) {
                            $total += (float) ($paid_claim->total / $rate);
                        }
                        else $total += (float) $paid_claim->total;
                    }
                }

                $paid_mps = MobilePayment::whereMonth('management_approval_at', '=', date('m'))->whereYear('management_approval_at', '=', date('Y'))->get();
                foreach($paid_mps as $paid_mp){
                    if($paid_mp->currency_id == 1) {
                        $total += (float) ($paid_mp->totals / $rate);
                    }
                    else $total += (float) $paid_mp->totals;
                }
            }
        }
        
        return round($total, 2);
    }
}
