<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Credit;
use App\Models\CustomerBalance;
use App\Models\Setting;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Facades\Cache;

class CreditService
{

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
        if ($car->latestCredit || $car->secondLatestCredit?->isNotEmpty()) {
            $dailyPercent = $car->latestCredit->monthly_percent * 12 / 365;
            // only admin can change or add payment date when creating payment
            $paymentDate    = $date ? Carbon::parse($date) : Carbon::now();
            $creditDays     = $paymentDate->diffInDays(Carbon::parse($car->latestCredit->issue_or_payment_date));
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
    public function totalAccruedInterestTillToday($car_id)
    {
        $car = Car::where('id', $car_id)
            ->with('latestCredit')
            ->first();

        $lastcalcalcDate = Carbon::parse($car?->latestCredit?->issue_or_payment_date);

        $today                     = Carbon::now();
        $totaldaysFromLastCalcDays = $today->diffInDays($lastcalcalcDate);
        $lastCreditAmount          = $car?->latestCredit?->credit_amount;
        $percent                   = $car?->latestCredit?->monthly_percent;
        $lastTotalAccruedPercent   = $car?->credit->sum('accrued_percent');

        $accruedPercentTillToday = $lastCreditAmount * ($percent * 12 / 365) * $totaldaysFromLastCalcDays + $lastTotalAccruedPercent;

        return $accruedPercentTillToday;
    }

    /**
     *   Total Interest Till certain date
     */
    public function totalAccruedInterestTillDate($car_id, $date)
    {
        $car = Car::where('id', $car_id)
            ->with('latestCredit')
            ->first();

        $lastcalcalcDate           = $car?->latestCredit?->issue_or_payment_date;
        $today                     = Carbon::parse($date);
        $totaldaysFromLastCalcDays = $today->diffInDays($lastcalcalcDate);
        $lastCreditAmount          = $car?->latestCredit?->credit_amount;
        $percent                   = $car?->latestCredit?->monthly_percent;
        $lastTotalAccruedPercent   = $car?->credit->sum('accrued_percent');
        $accruedPercentTillToday   = $lastCreditAmount * ($percent * 12 / 365) * $totaldaysFromLastCalcDays + $lastTotalAccruedPercent;

        return $accruedPercentTillToday;
    }

    /**
     *   Total Interest Till Today
     */
    public function totalInterestFromLastCalc($car_id)
    {
        $car                       = Car::where('id', $car_id)
            ->with('latestCredit')
            ->first();
        $lastcalcalcDate           = $car?->latestCredit?->issue_or_payment_date;
        $today                     = Carbon::now();
        $totaldaysFromLastCalcDays = $today->diffInDays($lastcalcalcDate);
        $lastCreditAmount          = $car?->latestCredit?->credit_amount;
        $percent                   = $car?->latestCredit?->monthly_percent;
        $lastTotalAccruedPercent   = $car?->credit->sum('accrued_percent');
        $accruedPercentTillToday   = $lastCreditAmount * ($percent * 12 / 365) * $totaldaysFromLastCalcDays;

        return $accruedPercentTillToday;
    }

    public function totalDaysFromLastCalcDate($car_id)
    {
        $car                       = Car::where('id', $car_id)
            ->with('latestCredit')
            ->first();
        $lastcalcalcDate           = $car?->latestCredit?->issue_or_payment_date;
        $today                     = Carbon::now();
        $totaldaysFromLastCalcDays = $today->diffInDays($lastcalcalcDate);

        return $totaldaysFromLastCalcDays;
    }

    // if payemnt was updated
    public function reCalculateOnPaymentUpdate(
        object $car,
        object $payment,
        $amount = null,
        $comment = null,
        $payment_date = null,
    ) {
        // first update old credit record
        $oldrecord                        = Credit::where('car_id', $car->id)
            ->where('customer_balance_id', $payment->id)->first();
        $oldrecord->paid_amount           = $amount;
        $oldrecord->issue_or_payment_date = Carbon::parse($payment_date);
        $oldrecord->comment               = $comment;
        $oldrecord->save();

        // Update balance payment and update car amount due

        if (($payment->amount) * -1 < $amount) {
            $amountToBeAdded = $amount - ($payment->amount) * -1;
            $car->amount_due -= $amountToBeAdded;
            $car->save();
        }

        // if previous amount was greater than new amount
        if (($payment->amount) * -1 > $amount) {
            $amountToBeRemoved = ($payment->amount) * -1 - $amount;
            $car->amount_due   += $amountToBeRemoved;
            $car->save();
        }

        // CustomerBalance
        $payment->carpayment_date = $payment_date;
        $payment->amount          = -$amount;
        $payment->save();


        $credit = Credit::where('car_id', $car->id)
            ->where('customer_id', $car->customer_id)
            ->get();


// ცალკე ვინახავ ძველ მონაცემს გარდა გააფდეითებულისა , ვუმატებ გააფდეითებულს ,
// ვსორტავ issue_or_payment_date ით ,
// ვშლი ყველა ძველ მონაცემს და ვატარებ შენახულ მონაცემებს ბაზაში


        $sortedCredit = $credit->sortBy('issue_or_payment_date')->values();

        // sorting needed if payment date is changed
        $creditAmount = $sortedCredit->first()['credit_amount'];

        // Recalculate interests for each record
        foreach ($sortedCredit as $index => $cr) {
            if ($index >= 1) {
                $paymentDate = Carbon::parse($cr['issue_or_payment_date']);
                $creditDays  = $paymentDate->diffInDays(Carbon::parse($sortedCredit[$index - 1]['issue_or_payment_date']));

                $accruedPercent = $creditAmount * ($cr['monthly_percent'] * 12 / 365) * $creditDays;
//                $creditAmount   += $accruedPercent - $cr['paid_amount'] +$sortedCredit[$index - 1]['added_amount'];
                $creditAmount += $accruedPercent - $cr['paid_amount'] + $cr->added_amount;

                $cr->credit_amount   = $creditAmount;
                $cr->accrued_percent = $accruedPercent;
                $cr->credit_days     = $creditDays;
                $cr->save();
            }
        }
    }


    // General Recalculation ???
    public function reCalculateOnDeleteOrAdd(object $car, object $newCredit = null, $costs = null)
    {
        $credit = Credit::where('car_id', $car->id)
            ->where('customer_id', $car->customer_id)
            ->get();


        // Add new costs
        if ($credit->isNotEmpty()) {
            if ($costs !== null) {
                $oldcosts=$credit->where('added_amount', '!=', 0);
                $initialAmount=Credit::where('car_id', $car->id)->first();


                // First delete all costs before , because also amount might be changed and its easier this way
                if($oldcosts){
                    foreach($oldcosts as $oldcost){
                        Credit::where('id', $oldcost->id)->delete();
                    }
                }

                $newamount=0;
                foreach ($costs as $cost) {
                    if (carbon::parse($cost['date']) != carbon::parse($credit->first()['issue_or_payment_date'])) {
                        $this->addNewAmountToCredit($car, $cost['value'], $cost['date'], $cost['name']);
                    } else {
                        $newamount += $cost['value'];
                        $initialAmount->credit_amount =$newamount;
                        $initialAmount->save();
                    }
                }
            }
        }


        $sortedCredit =  Credit::where('car_id', $car->id)
            ->where('customer_id', $car->customer_id)
            ->get()->sortBy('issue_or_payment_date')->values();

        // sorting needed if payment date is changed because interest recalculation
        if ($sortedCredit->first() !== null) {
            $creditAmount = $sortedCredit->first()['credit_amount'];
            // Recalculate interests for each record
            foreach ($sortedCredit as $index => $cr) {
                if ($index >= 1) {
                    $paymentDate = Carbon::parse($cr['issue_or_payment_date']);
                    $creditDays  = $paymentDate->diffInDays(Carbon::parse($sortedCredit[$index - 1]['issue_or_payment_date']));

                    $accruedPercent = $creditAmount * ($cr['monthly_percent'] * 12 / 365) * $creditDays;
                    $creditAmount += $accruedPercent - $cr['paid_amount'] + $cr['added_amount'];

                    $cr->credit_amount   = $creditAmount;
                    $cr->accrued_percent = $accruedPercent;
                    $cr->credit_days     = $creditDays;
                    $cr->save();
                    $car->amount_due = round($creditAmount);
                    $car->save();
                }
            }
        }
    }
}