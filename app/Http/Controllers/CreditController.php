<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function giveCredit(Request $request)
    {

//    dd($request->all());

        $credit = Credit::create([
            'customer_id'           => $request->customer_id,
            'credit_amount'         => $request->amount,
            'car_id'                => $request->car_id,
            'monthly_percent'       => $request->percent/100,
            'issue_or_payment_date' => $request->issue_date,
            'comment'=>$request->comment
        ]);

        return back();
    }
}
