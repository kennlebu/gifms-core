<?php

namespace App\Http\Controllers;

use App\Models\BankingModels\BankProjectBalances;
use App\Models\FinanceModels\ExchangeRate;
use App\Models\InvoicesModels\Invoice;
use App\Models\PaymentModels\Payment;
use App\Models\PaymentModels\PaymentBatch;
use DateTime;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function getMetrics(){
        $metrics = [];

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
            $metrics[] = ['title'=>'Bank balance estimate', 'total_balance'=>0, 'beginning_balance'=>0, 'accruals'=>0, 'unpaid'=>$unpaid_total, 'paid'=>$paid_total, 'action'=>'Add bank balance', 'current'=>$current];
        }
        else{
            $current = $bank_balance->total_balance ?? 0 - $bank_balance->accruals ?? 0 - $unpaid_total - $paid_total;
            $metrics[] = ['id'=>$bank_balance->id, 'title'=>'Bank balance estimate', 'total_balance'=>$bank_balance->total_balance, 'beginning_balance'=>$bank_balance->balance, 'accruals'=>$bank_balance->accruals ?? 0, 'unpaid'=>$unpaid_total, 'paid'=>$paid_total, 'current'=>$current];
        }

        return response()->json($metrics, 200,array(),JSON_PRETTY_PRINT);
    }
}
