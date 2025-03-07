<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Credit;
use App\Models\CustomerBalance;
use App\Models\Setting;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CreditService
{

    private ?Car $cachedCar = null;

    private function getCarById($car_id)
    {
        if (!$this->cachedCar || $this->cachedCar->id !== $car_id) {
            $this->cachedCar = Car::where('id', $car_id)
                ->with('latestCredit')
                ->first();
        }

        return $this->cachedCar;
    }

    public function totalAccruedInterestTillToday($car)
    {
        if (!($car instanceof Car)) {
            $car = $this->getCarById($car); // Fetch the Car object if ID is provided
        }

        $lastTotalAccruedPercent   = $car?->credit->sum('accrued_percent');
        $accruedPercentTillToday = $this->totalInterestFromLastCalc($car) + $lastTotalAccruedPercent;
        return $accruedPercentTillToday;

    }

    public function currentCreditAmount($car)
    {
        return round($car->latestCredit->credit_amount + $this->totalInterestFromLastCalc($car));
    }

    public function totalInterestFromLastCalc($car)
    {
        if (!($car instanceof Car)) {
            $car = $this->getCarById($car);
        }


        $lastcalcalcDate           = $car?->latestCredit?->issue_or_payment_date;
        $today                     = Carbon::now()->startOfDay();
        $totaldaysFromLastCalcDays = $lastcalcalcDate?->startOfDay()->diffInDays($today);

        $lastCreditAmount = $car?->latestCredit?->credit_amount;
        $percent          = $car?->latestCredit?->monthly_percent;
//        $lastTotalAccruedPercent   = $car?->credit->sum('accrued_percent');
        $accruedPercentTillToday = $lastCreditAmount * ($percent * 12 / 365) * round($totaldaysFromLastCalcDays);

        return $accruedPercentTillToday;
    }

    // shows just total days passed since last calculation
    public function totalDaysFromLastCalcDate($car)
    {
        if (!($car instanceof Car)) {
            $car = $this->getCarById($car);
        }


        $lastcalcalcDate           = $car?->latestCredit?->issue_or_payment_date;
        $today                     = Carbon::now()->startOfDay();
        $totaldaysFromLastCalcDays = $lastcalcalcDate?->startOfDay()->diffInDays($today);

        return round($totaldaysFromLastCalcDays);
    }


    // TOTAL RECALCULATION
    function calculateCreditBalance($initialAmount, $creditIssueDate, $costs, $payments, $annualRate = 0.48)
    {
//        dd($initialAmount);
        $events  = [];
        $balance = $initialAmount;
//        dd($balance);
        $lastDate        = $creditIssueDate; // Start from the credit issue date
        $result          = [];
        $accruedInterest = 0;  // Variable to track the interest for each day

        // Convert annual rate to a daily rate
        $dailyRate = $annualRate / 365; // 48% yearly -> 0.001315 daily



        // Step 1: Identify the first occurring date in $costs
        $firstCostDate = null;
        if (!empty($costs)) {
            $firstCostDate = min(array_column($costs, 'date')); // Get the earliest date
        }

        // ADD COSTS
        foreach ($costs as $cost) {
            if ($cost->date === $firstCostDate) {
                continue; // Skip all costs from the first date
            }

            if ($cost->forcredit == 1) {
                $events[] = ['type' => 'cost', 'amount' => $cost->value, 'date' => $cost->date];
            }
        }

        // ADD PAYMENTS
        foreach ($payments as $payment) {
            $events[] = [
                'type'       => 'payment',
                'payment_id' => $payment->payment_id,  // Include payment ID
                'amount'     => $payment->value,
                // Access the "value" instead of "amount" if you're transforming the data
                'date'       => $payment->date,
            ];
        }

        // SORT PAYMENTS AND COSTS
        usort($events, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        // Step 5: Process each event and calculate interest for the period between consecutive events
        $sumPayments = 0;

        foreach ($events as $index => $event) {
            $currentDate = $event['date'];

            // Format the current date if it's a Carbon instance
            if ($currentDate instanceof \Carbon\Carbon) {
                $currentDate = $currentDate->format('Y-m-d');
            }

            // Calculate the number of days between the last event and this one
            if ($lastDate) {
//                $daysElapsed = (strtotime($currentDate) - strtotime($lastDate)) / 86400; // Convert to days
                $daysElapsed = Carbon::parse($lastDate)->startOfDay()->diffInDays(Carbon::parse($currentDate)->startOfDay());

                if ($daysElapsed > 0) {
                    // Calculate interest for the period between lastDate and currentDate
                    $interest        = $balance * $dailyRate * $daysElapsed;
                    $balance         += $interest;  // Add the calculated interest to the balance
                    $accruedInterest = $interest;  // Track the interest for the current period
                } else {
                    $accruedInterest = 0;
                }
            }

            // Track the changes from cost or payment
            $costAmount    = 0;
            $paymentAmount = 0;
            if ($event['type'] === 'cost') {
                $balance    += $event['amount'];  // Add cost to balance
                $costAmount = $event['amount']; // Track the cost amount
            }
//            elseif ($event['type'] === 'payment') {
//                $balance       -= $event['amount'];  // Subtract payment from balance
//                $paymentAmount = $event['amount']; // Track the payment amount
//            }

            elseif ($event['type'] === 'payment') {
                if ($event['amount'] > $balance) {
                    $paymentAmount = $balance;
                    $balance       = 0;

                    // Add the result for this final payment
                    $result[] = [
                        'date'             => $currentDate,
                        'balance'          => round($balance, 2),
                        'cost'             => $costAmount,
                        'payment'          => round($paymentAmount),
                        'accrued_interest' => round($accruedInterest, 2),
                        'credit_days'      => $daysElapsed,
                        'payment_id'       => $event['payment_id'] ?? null,
                    ];

                    break; // Exit the loop since balance is zero
                } else {
                    $balance       -= round($event['amount']);
                    $paymentAmount = round($event['amount']);
                }
            }


            // Add the result for the current date including accrued interest and date difference
            $result[] = [
                'date'             => $currentDate,  // Ensure date is a string
                'balance'          => round($balance, 2),
                'cost'             => $costAmount,
                'payment'          => $paymentAmount,
                'accrued_interest' => round($accruedInterest, 2),  // Show interest calculated for this period
                'credit_days'      => $daysElapsed, // Add date difference from the last date
                'payment_id'       => $event['payment_id'] ?? null, // Add payment_id if it's a payment event
            ];

            // Update the last date for the next iteration
            $lastDate = $currentDate;
        }

        return $result;
    }

    public function recalc($car, $monthly_percent = 0.04)
    {
        if (!($car instanceof Car)) {
            $car = $this->getCarById($car);
        }

        $costs    = json_decode($car->balance_accounting);
        $payments = $car->payments;



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


//dd($costs[0]->forcredit==0);
        $issueDate = $costs[0]->date;
        $percent   = $monthly_percent;
        $car->credit()?->delete();


        $initialCreditAmount = 0;
        foreach ($costs as $cost) {
            if ($cost->date == $issueDate && $cost->forcredit == 1) {
                $initialCreditAmount += $cost->value;
            }
        }

        // Create First Credit Record
        Credit::create([
            'customer_id'           => $car->customer->id,
            'credit_amount'         => $initialCreditAmount,
            'car_id'                => $car->id,
            'monthly_percent'       => $percent,
            'issue_or_payment_date' => $issueDate,
        ]);


        $calculatedBalance = $this->calculateCreditBalance($initialCreditAmount, $issueDate, $costs, $paymentsArray,
            $percent * 12,);


        $processedDates = []; // Track processed dates for accrued_percent


//        dd($excludedCost);

        foreach ($calculatedBalance as $index => $newRecord) {
            // Check if this date has been processed before
            $applyAccruedPercent = !in_array($newRecord['date'], $processedDates);


            // If already processed, set accrued_interest to 0
            if (!$applyAccruedPercent) {
                $newRecord['accrued_interest'] = 0;
            }

            // Mark this date as processed
            $processedDates[] = $newRecord['date'];

            if ($newRecord['payment'] === 0) {
                Credit::create([
                    'issue_or_payment_date' => $newRecord['date'],
                    'car_id'                => $car->id,
                    'customer_id'           => $car->customer->id,
                    'credit_amount'         => round($newRecord['balance']),
                    'monthly_percent'       => $percent,
                    'accrued_percent'       => round($newRecord['accrued_interest']), // Only applied once per date
                    'credit_days'           => $newRecord['credit_days'],
                    'added_amount'          => $newRecord['cost'],
                ]);
            } else {
                Credit::create([
                    'issue_or_payment_date' => $newRecord['date'],
                    'car_id'                => $car->id,
                    'customer_id'           => $car->customer->id,
                    'credit_amount'         => round($newRecord['balance']),
                    'monthly_percent'       => $percent,
                    'accrued_percent'       => round($newRecord['accrued_interest']), // Only applied once per date
                    'credit_days'           => $newRecord['credit_days'],
                    'paid_amount'           => $newRecord['payment'],
                    'customer_balance_id'   => $newRecord['payment_id'],
                ]);
            }

            $excludedCost = 0;
            foreach ($costs as $cost) {
                if ($cost->forcredit == 0) {
                    $excludedCost += $cost->value;
                }
            }

            if (!empty($calculatedBalance)) {
                $lastRecord = end($calculatedBalance); // Get last record
                if ($lastRecord['balance'] == 0) {

                    $totalAccruedInterest = array_sum(array_column($calculatedBalance, 'accrued_interest'));
                    $totalCost            = $car->total_cost;
                    $totalPayments        = $car->payments->where('type', 'car_payment')->sum('amount');
                    $amount_due           = $totalCost + $totalAccruedInterest + $totalPayments;
                } else {
                    $amount_due = round($lastRecord['balance'] +$excludedCost);
                }
//dd($amount_due);
                $car->amount_due = ($lastRecord['payment'] == $car->amount_due ? 0 : round($amount_due));
                $car->save();
            }
        }


        return back();
    }


}