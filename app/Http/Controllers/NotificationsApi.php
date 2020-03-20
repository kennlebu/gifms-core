<?php

namespace App\Http\Controllers;

use App\Models\BankingModels\BankProjectBalances;
use App\Models\BankingModels\CashReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NotificationsApi extends Controller
{
    public function checkMonthBalance(Request $request){
        if(empty($request->add_cash_received)){
            $bank_balance = BankProjectBalances::whereMonth('balance_date', date('m'))->whereYear('balance_date', date('Y'))->first();
            if(empty($bank_balance)){
                return Response()->json(['is_set'=>false], 200);
            }
            else {
                return Response()->json(['is_set'=>true], 200);
            }
        }
        else {
            $bank_balance = BankProjectBalances::whereMonth('balance_date', date('m', strtotime($request->date)))->whereYear('balance_date', date('Y', strtotime($request->date)))->first();
            $cash_received = new CashReceived();
            $cash_received->amount = $request->amount;
            $cash_received->date = $request->date;
            $cash_received->bank_balance_id = $bank_balance->id;
            $cash_received->disableLogging();
            $cash_received->save();
            return Response()->json(['msg'=>'Success'], 200);
        }
    }
}
