<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Credit;
use App\Models\CustomerBalance;
use App\Services\CreditService;
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

    public function removeCredit(Request $request){

        $car = Car::find($request->car_id);
        $car->credit()->delete();
        $payments=CustomerBalance::where('car_id', $request->car_id)->where('type', 'car_payment')->get();
        $amountDue=$car->total_cost;
        foreach ($payments as $payment) {
            $amountDue+=$payment->amount;
        }

        $car->amount_due=$amountDue;
        $car->save();


        return back();

    }



  public function newPercent(Request $request){

      (new CreditService())->recalc( $request->car_id, $request->new_percent/100);

      return back();

  }


    public function totalRecalc(Request $request){

        (new CreditService())->recalc($request->car_id);

        return back();

    }
}
