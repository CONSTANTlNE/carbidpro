<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Credit;
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


// First Version without ??
//    function calculateCreditBalance($initialAmount, $creditIssueDate, $costs, $payments, $annualRate = 0.48)
//    {
//        $events = [];
//        $balance = $initialAmount;
//        $lastDate = $creditIssueDate; // Start from the credit issue date
//        $result = [];
//        $totalInterest = 0;  // Variable to track the total interest accrued
//
//        // Convert annual rate to a daily rate
//        $dailyRate = $annualRate / 365; // 48% yearly -> 0.001315 daily
//
//        // Step 1: Identify the first occurring date in $costs
//        $firstCostDate = null;
//        if (!empty($costs)) {
//            $firstCostDate = min(array_column($costs, 'date')); // Get the earliest date
//        }
//
//        // Step 2: Process costs (skip all costs with first occurring date)
//        foreach ($costs as $cost) {
//            if ($cost->date === $firstCostDate) {
//                continue; // Skip all costs from the first date
//            }
//            $events[] = ['type' => 'cost', 'amount' => $cost->value, 'date' => $cost->date];
//        }
//
//        // Step 3: Add payments to the timeline
//        foreach ($payments as $payment) {
//            $events[] = [
//                'type' => 'payment',
//                'amount' => $payment->value, // Access the "value" instead of "amount" if you're transforming the data
//                'date'  => $payment->date,
//            ];
//        }
//
//        // Step 4: Sort events by date
//        usort($events, function ($a, $b) {
//            return strtotime($a['date']) - strtotime($b['date']);
//        });
//
//        // Step 5: Process each event and calculate interest daily
//        foreach ($events as $event) {
//            $currentDate = $event['date'];
//
//            if ($lastDate) {
//                $daysElapsed = (strtotime($currentDate) - strtotime($lastDate)) / 86400;
//                if ($daysElapsed > 0) {
//                    $interest = $balance * $dailyRate * $daysElapsed;
//                    $balance += $interest;
//                    $totalInterest += $interest;  // Accumulate interest
//                }
//            }
//
//            // Track the changes from cost or payment
//            $costAmount = 0;
//            $paymentAmount = 0;
//            if ($event['type'] === 'cost') {
//                $balance += $event['amount'];
//                $costAmount = $event['amount'];
//            } elseif ($event['type'] === 'payment') {
//                $balance -= $event['amount'];
//                $paymentAmount = $event['amount'];
//            }
//
//            // Add the date, balance, cost, payment, and accrued interest to the result
//            $result[] = [
//                'date' => $currentDate,
//                'balance' => round($balance, 2),
//                'cost' => $costAmount,
//                'payment' => $paymentAmount,
//                'accrued_interest' => round($totalInterest, 2),  // Add accrued interest here
//            ];
//
//            $lastDate = $currentDate;
//        }
//
//        return $result;
//    }
//

//Second Version  without credit days
//
//    function calculateCreditBalance($initialAmount, $creditIssueDate, $costs, $payments, $annualRate = 0.48)
//    {
//        $events = [];
//        $balance = $initialAmount;
//        $lastDate = $creditIssueDate; // Start from the credit issue date
//        $result = [];
//        $accruedInterest = 0;  // Variable to track the interest for each day
//
//        // Convert annual rate to a daily rate
//        $dailyRate = $annualRate / 365; // 48% yearly -> 0.001315 daily
//
//        // Step 1: Identify the first occurring date in $costs
//        $firstCostDate = null;
//        if (!empty($costs)) {
//            $firstCostDate = min(array_column($costs, 'date')); // Get the earliest date
//        }
//
//        // Step 2: Process costs (skip all costs with first occurring date)
//        foreach ($costs as $cost) {
//            if ($cost->date === $firstCostDate) {
//                continue; // Skip all costs from the first date
//            }
//            $events[] = ['type' => 'cost', 'amount' => $cost->value, 'date' => $cost->date];
//        }
//
//        // Step 3: Add payments to the timeline
//        foreach ($payments as $payment) {
//            $events[] = [
//                'type' => 'payment',
//                'amount' => $payment->value, // Access the "value" instead of "amount" if you're transforming the data
//                'date'  => $payment->date,
//            ];
//        }
//
//        // Step 4: Sort events by date
//        usort($events, function ($a, $b) {
//            return strtotime($a['date']) - strtotime($b['date']);
//        });
//
//        // Step 5: Process each event and calculate interest for the period between consecutive events
//        foreach ($events as $event) {
//            $currentDate = $event['date'];
//
//            // Format the current date if it's a Carbon instance
//            if ($currentDate instanceof \Carbon\Carbon) {
//                $currentDate = $currentDate->format('Y-m-d');
//            }
//
//            // Calculate the number of days between the last event and this one
//            if ($lastDate) {
//                $daysElapsed = (strtotime($currentDate) - strtotime($lastDate)) / 86400; // Convert to days
//                if ($daysElapsed > 0) {
//                    // Calculate interest for the period between lastDate and currentDate
//                    $interest = $balance * $dailyRate * $daysElapsed;
//                    $balance += $interest;  // Add the calculated interest to the balance
//                    $accruedInterest = $interest;  // Track the interest for the current period
//                }
//            }
//
//            // Track the changes from cost or payment
//            $costAmount = 0;
//            $paymentAmount = 0;
//            if ($event['type'] === 'cost') {
//                $balance += $event['amount'];  // Add cost to balance
//                $costAmount = $event['amount']; // Track the cost amount
//            } elseif ($event['type'] === 'payment') {
//                $balance -= $event['amount'];  // Subtract payment from balance
//                $paymentAmount = $event['amount']; // Track the payment amount
//            }
//
//            // Add the result for the current date including accrued interest
//            $result[] = [
//                'date' => $currentDate,  // Ensure date is a string
//                'balance' => round($balance, 2),
//                'cost' => $costAmount,
//                'payment' => $paymentAmount,
//                'accrued_interest' => round($accruedInterest, 2),  // Show interest calculated for this period
//            ];
//
//            // Update the last date for the next iteration
//            $lastDate = $currentDate;
//        }
//
//        return $result;
//    }



  public function newPercent(Request $request){



//      $request->validate([
//            'car_id' => 'required',
//            'new_percent' => 'required',
//        ])

      (new CreditService())->recalc( $request->car_id, $request->new_percent/100);

      return back();

  }







    public function totalRecalc(Request $request){


        (new CreditService())->recalc($request->car_id);

        return back();

    }
}
