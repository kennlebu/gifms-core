<?php

namespace App\Http\Controllers;

use App\Models\BankingModels\BankProjectBalances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NotificationsApi extends Controller
{
    public function checkMonthBalance(){
        $bank_balance = BankProjectBalances::whereMonth('balance_date', date('m'))->whereYear('balance_date', date('Y'))->first();
        if(empty($bank_balance)){
            return Response()->json(['is_set'=>false], 200);
        }
        else {
            return Response()->json(['is_set'=>true], 200);
        }
    }
}
