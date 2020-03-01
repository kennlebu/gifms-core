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
        $invoices_total = 0;
        $end_date = new DateTime(date('Y-m-d'));
        $end_date = $end_date->format('Y-m-t')." 23:59:59";
        $start_date = date('Y-m').'-01 00:00:00';

        // $batches = PaymentBatch::whereBetween('created_at', [$start_date, $end_date])->pluck('id')->toArray();
        // if(!empty($batches)){
        //     $invoice_ids = Payment::where('payable_type', 'invoices')->wherein('id', $batches)->pluck('payable_id')->toArray();
        // }

        $invoices = Invoice::whereIn('status_id', [1,2,3,4,9,10,11,12,13])->whereMonth('created_at', [$start_date, $end_date])
                    ->orWhere(function ($query) {
                        $query->where('id', 5);
                    })->get();
        // if(!empty($invoice_ids)){
        //     $invoices = $invoices->orWhereIn('id', $invoice_ids);
        // }
        // $invoices = $invoices->get();

        foreach($invoices as $invoice){
            if($invoice->currency_id == 1) {
                $rate = ExchangeRate::whereMonth('active_date', date('m'))->orderBy('active_date', 'DESC')->first();
                if(!empty($rate)) $rate = $rate->exchange_rate;
                else $rate = 101.70;

                $invoices_total += (float) ($invoice->total / $rate);
            }
            else $invoices_total += (float) $invoice->total;
        }
        
        $bank_balance = BankProjectBalances::whereMonth('balance_date', date('m'))->whereYear('balance_date', date('Y'))->first();
        
        if(empty($bank_balance) || $bank_balance->balance == 0)
            $metrics[] = ['title'=>'Bank balance estimate', 'balance'=>0, 'total'=>$invoices_total, 'action'=>'Add bank balance'];
        else
            $metrics[] = ['title'=>'Bank balance estimate', 'balance'=>$bank_balance->balance, 'total'=>$invoices_total];

        return response()->json($metrics, 200,array(),JSON_PRETTY_PRINT);
    }
}
