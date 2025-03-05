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




    public function creditPayment(object $car, object $request, object $balance)
    {
        if ($car->latestCredit) {
            $dailyPercent = $car->latestCredit->monthly_percent * 12 / 365;
            // only admin can change or add payment date when creating payment
            $paymentDate    = $request->payment_date ? Carbon::parse($request->payment_date) : Carbon::now();
            $creditDays     = $paymentDate->diffInDays(Carbon::parse($car->latestCredit->issue_or_payment_date));
            $accruedPercent = $car->latestCredit->credit_amount * $dailyPercent * $creditDays;

            $newCreditRecord = Credit::create([
                'issue_or_payment_date' => $paymentDate,
                'car_id'                => $car->id,
                'customer_id'           => $car->customer->id,
                'customer_balance_id'   => $balance?->id,
                'paid_amount'           => $request->amount,
                'monthly_percent'       => $request->monthly_percent ?: $car->latestCredit->monthly_percent,
                'credit_amount'         => $car->latestCredit->credit_amount + $accruedPercent - $request->amount,
                'accrued_percent'       => $accruedPercent,
                'credit_days'           => $creditDays,
                'comment'               => $request->comment,
            ]);


            return $newCreditRecord;
        }

        return null;
    }


    // For Adding Single cost
    public function addNewAmountToCredit(object $car, string $amount, $date = null, string $comment = null)
    {
        // To ensure that the $car variable always holds the updated value from the database after performing any action  $car->refresh();
        $car->refresh();
        if ($car->latestCredit ) {

            $dailyPercent = $car->latestCredit->monthly_percent * 12 / 365;
            // only admin can change or add payment date when creating payment
            $paymentDate    = $date ? Carbon::parse($date) : Carbon::now();
            $creditDays     = -$paymentDate->diffInDays(Carbon::parse($car->latestCredit->issue_or_payment_date));

            $accruedPercent = $car->latestCredit->credit_amount * $dailyPercent * $creditDays;

            $newCreditRecord = Credit::create([
                'issue_or_payment_date' => $paymentDate,
                'car_id'                => $car->id,
                'customer_id'           => $car->customer->id,
                'monthly_percent'       => $car->latestCredit->monthly_percent,
                'credit_amount'         => $car->latestCredit->credit_amount + $accruedPercent + $amount,
                'accrued_percent'       => $accruedPercent,
                'added_amount'          => $amount,
                'credit_days'           => $creditDays,
                'comment'               => $comment ?: 'Storage',
            ]);

            return $newCreditRecord;
        }
        return null;
    }

    /**
     *   Total Interest Till Today
     */
    public function totalAccruedInterestTillToday($car)
    {



        $lastcalcalcDate = Carbon::parse($car?->latestCredit?->issue_or_payment_date);

        $today                     = Carbon::now()->startOfDay();
        $totaldaysFromLastCalcDays = $lastcalcalcDate?->startOfDay()->diffInDays($today);
        $lastCreditAmount          = $car?->latestCredit?->credit_amount;
        $percent                   = $car?->latestCredit?->monthly_percent;
        $lastTotalAccruedPercent   = $car?->credit->sum('accrued_percent');

        $accruedPercentTillToday = $lastCreditAmount * ($percent * 12 / 365) * round($totaldaysFromLastCalcDays) + $lastTotalAccruedPercent;

        return $accruedPercentTillToday;
    }


    public function totalInterestFromLastCalc($car_id)
    {
//        $car                       = Car::where('id', $car_id)
//            ->with('latestCredit')
//            ->first();

        $car = $this->getCarById($car_id);


        $lastcalcalcDate           = $car?->latestCredit?->issue_or_payment_date;
        $today                     = Carbon::now()->startOfDay();
        $totaldaysFromLastCalcDays = $lastcalcalcDate?->startOfDay()->diffInDays($today);

        $lastCreditAmount          = $car?->latestCredit?->credit_amount;
        $percent                   = $car?->latestCredit?->monthly_percent;
//        $lastTotalAccruedPercent   = $car?->credit->sum('accrued_percent');
        $accruedPercentTillToday   = $lastCreditAmount * ($percent * 12 / 365) * round($totaldaysFromLastCalcDays);

        return $accruedPercentTillToday;
    }

    // shows just total days passed since last calculation
    public function totalDaysFromLastCalcDate($car_id)
    {
        $car = $this->getCarById($car_id);


        $lastcalcalcDate           = $car?->latestCredit?->issue_or_payment_date;
        $today                     = Carbon::now()->startOfDay();
        $totaldaysFromLastCalcDays = $lastcalcalcDate?->startOfDay()->diffInDays($today);

        return  round($totaldaysFromLastCalcDays);
    }




    // TOTAL RECALCULATION
    function calculateCreditBalance($initialAmount, $creditIssueDate, $costs, $payments, $annualRate = 0.48)
    {

        $events = [];
        $balance = $initialAmount;
//        dd($balance);
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

            if ($cost->forcredit == 1) {
                $events[] = ['type' => 'cost', 'amount' => $cost->value, 'date' => $cost->date];
            }
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

//            dd($currentDate, $lastDate);


            // Calculate the number of days between the last event and this one
            if ($lastDate) {

//                $daysElapsed = (strtotime($currentDate) - strtotime($lastDate)) / 86400; // Convert to days
                $daysElapsed = Carbon::parse($lastDate)->diffInDays(Carbon::parse($currentDate)) ;

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

    public function recalc($car_id, $monthly_percent=0.04){


        $car=Car::where('id',$car_id)->with('credit','payments','customer')->first();
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


//dd($costs[0]->forcredit==0);
        $issueDate=$costs[0]->date;
        $percent=$monthly_percent;
        $car->credit()?->delete();



        $initialCreditAmount=0;
        foreach ($costs as $cost){
            if ($cost->date == $issueDate && $cost->forcredit == 1) {
                $initialCreditAmount+=$cost->value;
            }
        }


        $newCredit = Credit::create([
            'customer_id'           => $car->customer->id,
            'credit_amount'         => $initialCreditAmount,
            'car_id'                => $car->id,
            'monthly_percent'       => $percent,
            'issue_or_payment_date' => $issueDate,

        ]);




        $calculatedBalance = $this->calculateCreditBalance($initialCreditAmount,$issueDate, $costs, $paymentsArray,$percent*12);

//dd($calculatedBalance);

        // add result to credit both.. costs and payments
//        foreach ($calculatedBalance as $index => $newRecord) {
//            if ($newRecord['payment']===0){
//                // if payment is more than credit amount , occurs when shipping or other cost is not included
//                if ($newRecord['payment'] > $newRecord['balance']) {
//                    $newRecord['payment'] = round($newRecord['balance']+$newRecord['payment']);
//                    $newRecord['balance']=0;
//                    $newRecord['accrued_interest']=0;
//                }
//                Credit::create([
//                    'issue_or_payment_date'=>$newRecord['date'],
//                    'car_id'=>$car->id,
//                    'customer_id'=>$car->customer->id,
//                    'credit_amount'=>round($newRecord['balance']),
//                    'monthly_percent'=>$percent,
//                    'accrued_percent'=>round($newRecord['accrued_interest']),
//                    'credit_days'=>$newRecord['credit_days'],
//                    'added_amount'=>$newRecord['cost'],
//                ]);
//            } else {
//                // if payment is more than credit amount , occurs when shipping or other cost is not included
//                if ($newRecord['payment'] > $newRecord['balance']) {
//                    $newRecord['payment'] = round($newRecord['balance']+$newRecord['payment']);
//                    $newRecord['balance']=0;
//                    $newRecord['accrued_interest']=0;
//                }
//
//                Credit::create([
//                    'issue_or_payment_date'=>$newRecord['date'],
//                    'car_id'=>$car->id,
//                    'customer_id'=>$car->customer->id,
//                    'credit_amount'=>round($newRecord['balance']),
//                    'monthly_percent'=>$percent,
//                    'accrued_percent'=>round($newRecord['accrued_interest']),
//                    'credit_days'=>$newRecord['credit_days'],
//                    'paid_amount'=>$newRecord['payment'],
//                    'customer_balance_id'=>$newRecord['payment_id']
//                ]);
//            }
//
//            $lastIndex = count($calculatedBalance) - 1;
//
//
//            if ($index === $lastIndex){
//                if ($newRecord['payment'] > $newRecord['balance']) {
//                    $car->amount_due=0;
//                    $car->save();
//                } else {
//                    $car->amount_due=round($newRecord['balance']);
//                    $car->save();
//                }
//            }
//
//        }

        $processedDates = []; // Track processed dates for accrued_percent

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
                if ($newRecord['payment'] > $newRecord['balance']) {
                    $newRecord['payment'] = round($newRecord['balance'] + $newRecord['payment']);
                    $newRecord['balance'] = 0;
                    $newRecord['accrued_interest'] = 0;
                }

                Credit::create([
                    'issue_or_payment_date' => $newRecord['date'],
                    'car_id' => $car->id,
                    'customer_id' => $car->customer->id,
                    'credit_amount' => round($newRecord['balance']),
                    'monthly_percent' => $percent,
                    'accrued_percent' => round($newRecord['accrued_interest']), // Only applied once per date
                    'credit_days' => $newRecord['credit_days'],
                    'added_amount' => $newRecord['cost'],
                ]);
            } else {
                if ($newRecord['payment'] > $newRecord['balance']) {
                    $newRecord['payment'] = round($newRecord['balance'] + $newRecord['payment']);
                    $newRecord['balance'] = 0;
                    $newRecord['accrued_interest'] = 0;
                }

                Credit::create([
                    'issue_or_payment_date' => $newRecord['date'],
                    'car_id' => $car->id,
                    'customer_id' => $car->customer->id,
                    'credit_amount' => round($newRecord['balance']),
                    'monthly_percent' => $percent,
                    'accrued_percent' => round($newRecord['accrued_interest']), // Only applied once per date
                    'credit_days' => $newRecord['credit_days'],
                    'paid_amount' => $newRecord['payment'],
                    'customer_balance_id' => $newRecord['payment_id']
                ]);
            }

            // Update amount_due in Car model at the last record
            $lastIndex = count($calculatedBalance) - 1;
            if ($index === $lastIndex) {
                $car->amount_due = ($newRecord['payment'] > $newRecord['balance']) ? 0 : round($newRecord['balance']);
                $car->save();
            }
        }





        return back();

    }





}