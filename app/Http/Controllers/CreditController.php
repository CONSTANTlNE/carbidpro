<?php

namespace App\Http\Controllers;

use App\Models\Car;
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

    function calculateCreditBalance($initialAmount, $creditIssueDate, $costs, $payments, $annualRate = 0.48)
    {
        $events = [];
        $balance = $initialAmount;
        $lastDate = $creditIssueDate; // Start from the credit issue date
        $result = [];
        $accruedInterest = 0;  // Variable to track the interest for each day

        // Convert annual rate to a daily rate
        $dailyRate = $annualRate / 365; // 48% yearly -> 0.001315 daily

        // Step 1: Identify the first occurring date in $costs
        $firstCostDate = null;
        if (!empty($costs)) {
            $firstCostDate = min(array_column($costs, 'date')); // Get the earliest date
        }

        // Step 2: Process costs (skip all costs with first occurring date)
        foreach ($costs as $cost) {
            if ($cost->date === $firstCostDate) {
                continue; // Skip all costs from the first date
            }
            $events[] = ['type' => 'cost', 'amount' => $cost->value, 'date' => $cost->date];
        }

        // Step 3: Add payments to the timeline
        foreach ($payments as $payment) {
            $events[] = [
                'type' => 'payment',
                'payment_id' => $payment->payment_id,  // Include payment ID
                'amount' => $payment->value, // Access the "value" instead of "amount" if you're transforming the data
                'date'  => $payment->date,
            ];
        }

        // Step 4: Sort events by date
        usort($events, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        // Step 5: Process each event and calculate interest for the period between consecutive events
        foreach ($events as $index => $event) {
            $currentDate = $event['date'];

            // Format the current date if it's a Carbon instance
            if ($currentDate instanceof \Carbon\Carbon) {
                $currentDate = $currentDate->format('Y-m-d');
            }

            // Calculate the number of days between the last event and this one
            if ($lastDate) {
                $daysElapsed = (strtotime($currentDate) - strtotime($lastDate)) / 86400; // Convert to days
                if ($daysElapsed > 0) {
                    // Calculate interest for the period between lastDate and currentDate
                    $interest = $balance * $dailyRate * $daysElapsed;
                    $balance += $interest;  // Add the calculated interest to the balance
                    $accruedInterest = $interest;  // Track the interest for the current period
                }
            }

            // Track the changes from cost or payment
            $costAmount = 0;
            $paymentAmount = 0;
            if ($event['type'] === 'cost') {
                $balance += $event['amount'];  // Add cost to balance
                $costAmount = $event['amount']; // Track the cost amount
            } elseif ($event['type'] === 'payment') {
                $balance -= $event['amount'];  // Subtract payment from balance
                $paymentAmount = $event['amount']; // Track the payment amount
            }

            // Add the result for the current date including accrued interest and date difference
            $result[] = [
                'date' => $currentDate,  // Ensure date is a string
                'balance' => round($balance, 2),
                'cost' => $costAmount,
                'payment' => $paymentAmount,
                'accrued_interest' => round($accruedInterest, 2),  // Show interest calculated for this period
                'credit_days' => $daysElapsed, // Add date difference from the last date
                'payment_id' => $event['payment_id'] ?? null, // Add payment_id if it's a payment event
            ];

            // Update the last date for the next iteration
            $lastDate = $currentDate;
        }

        return $result;
    }






    public function totalRecalc(Request $request){

//        vxcvxcv

        $car=Car::where('id',$request->car_id)->with('credit','payments','customer')->first();
        $costs=json_decode($car->balance_accounting) ;
        $payments=$car->payments;


        // Convert payments to positive
        $paymentsArray = $payments->filter(function ($payment) {
            return $payment->type === 'car_payment' && $payment->amount != 0;
        })->map(function ($payment) {
            return (object) [
                'payment_id' => $payment->id,  // Add payment ID
                'name'       => 'Car Payment',
                'value'      => abs($payment->amount), // Convert to positive amount
                'date'       => $payment->carpayment_date,
            ];
        })->values()->toArray();

//dd($paymentsArray);
        $issueDate=$costs[0]->date;
        $percent=0.48;

        $car->credit()?->delete();



        $initialCreditAmount=0;
        foreach ($costs as $cost){
            if ($cost->date == $issueDate){
                $initialCreditAmount+=$cost->value;
            }
        }


        $newCredit = Credit::create([
            'customer_id'           => $car->customer->id,
            'credit_amount'         => $initialCreditAmount,
            'car_id'                => $car->id,
            'monthly_percent'       => $percent/12,
            'issue_or_payment_date' => $issueDate,

        ]);




        $calculatedBalance = $this->calculateCreditBalance($initialCreditAmount,$issueDate, $costs, $paymentsArray,$percent,);

//dd($calculatedBalance);
        // add result to credit both.. costs and payments
        foreach ($calculatedBalance as $index => $newRecord) {
            if ($newRecord['payment']===0){
                Credit::create([
                   'issue_or_payment_date'=>$newRecord['date'],
                    'car_id'=>$car->id,
                    'customer_id'=>$car->customer->id,
                    'credit_amount'=>round($newRecord['balance']),
                    'monthly_percent'=>$percent/12,
                    'accrued_percent'=>round($newRecord['accrued_interest']),
                    'credit_days'=>$newRecord['credit_days'],
                    'added_amount'=>$newRecord['cost'],
                ]);
            } else {
                Credit::create([
                    'issue_or_payment_date'=>$newRecord['date'],
                    'car_id'=>$car->id,
                    'customer_id'=>$car->customer->id,
                    'credit_amount'=>round($newRecord['balance']),
                    'monthly_percent'=>$percent/12,
                    'accrued_percent'=>round($newRecord['accrued_interest']),
                    'credit_days'=>$newRecord['credit_days'],
                    'paid_amount'=>$newRecord['payment'],
                    'customer_balance_id'=>$newRecord['payment_id']
                ]);
            }

            $lastIndex = count($calculatedBalance) - 1;


            if ($index === $lastIndex){
                $car->amount_due=round($newRecord['balance']);
            }

        }






        return back();

    }
}
