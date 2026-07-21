<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Payment;

class HomeController extends Controller
{
    public function fetchAccountingData(){
        $accounts = Account::where('church_id',request('church_id'))->get();
        $transactions= Payment::where('user_id',request('user_id'))->with('account')->get();
        return response()->json([
            'accounts'=>$accounts,
            'transactions'=>$transactions
        ]);
    }
}
