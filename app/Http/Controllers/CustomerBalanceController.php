<?php

namespace App\Http\Controllers;

use App\Mail\SampleMail;
use App\Models\Car;
use App\Models\Credit;
use App\Models\Customer;
use App\Models\CustomerBalance;
use App\Models\MobileNumbers;
use App\Services\CreditService;
use App\Services\smsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerBalanceController extends Controller
{

    // ================ Balance Fills ====================

    public function index(Request $request)
    {
        if ($request->has('search') && !empty($request->search)) {
            $payment_requests = CustomerBalance::with(['customer.cars'])
                ->where('type', 'fill')
                ->where(function ($query) use ($request) {
                    $query
                        ->where('full_name', 'like', '%'.$request->search.'%')
                        ->orWhere('amount', 'like', '%'.$request->search.'%')
                        ->orWhere('created_at', 'like', '%'.$request->search.'%');
                })
                ->orWhereHas('customer', function ($query) use ($request) {
                    $query
                        ->where('company_name', 'like', '%'.$request->search.'%')
                        ->orWhere('contact_name', 'like', '%'.$request->search.'%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(50)
                ->withQueryString();

            return view('pages.balance_fills.index', compact('payment_requests'));
        }


        $payment_requests = CustomerBalance::with(['customer.cars'])
            ->where('type', 'fill')
            ->orderBy('created_at', 'desc')
            ->paginate(50)
            ->withQueryString();

        return view('pages.balance_fills.index', compact('payment_requests'));
    }

    // Store balance fill by admin
    public function storeBalance(Request $request)
    {
        $request->validate([
            'bank_payment' => 'required|numeric|min:1',
            'full_name'    => 'required|string|max:255',
        ]);

        $balance = new CustomerBalance;


        $balance->amount            = $request->bank_payment;
        $balance->full_name         = $request->full_name;
        $balance->date              = $request->payment_date ?: Carbon::now();
        $balance->balance_fill_date = $request->payment_date ?: Carbon::now();
        $balance->type              = 'fill';
        $balance->comment           = $request->comment;
        $balance->is_approved       = true;
        $balance->customer_id       = $request->customer_id;

        $balance->save();


        return back();
    }

    /**
     * Approve a payment request (general amount like filling balance)
     */
    public function approveBalance(Request $request)
    {
        $prequest = CustomerBalance::where('id', $request->id)->first();
        $customer = $prequest->customer;

        if ($prequest->is_approved === 0) {
            $prequest->is_approved = 1;
            $prequest->save();
            (new smsService())->depositConfirmationCustomer($customer->phone, $prequest);
        } else {
            $prequest->is_approved = 0;
            $prequest->save();
        }

        return back();
    }

    /**
     * General amount transfer (like filling balance by customer)
     */
    public function registrPaymentRequest(Request $request)
    {
        $request->validate([
            'bank_payment' => 'required|numeric|min:1',
            'full_name'    => 'required|string|max:255',
        ]);

        $balance = new CustomerBalance;

        $balance->amount            = $request->bank_payment;
        $balance->full_name         = $request->full_name;
        $balance->date              = $request->payment_date ?: Carbon::now();
        $balance->balance_fill_date = $request->payment_date ?: Carbon::now();
        $balance->type              = 'fill';
        $balance->customer_id       = auth()->user()->id;
        $balance->save();


        try {
            $content = [
                'name'   => $balance->full_name,
                'date'   => $balance->date,
                'amount' => $balance->amount,
            ];

            Mail::to(config('carbiddata.email'))->send(new SampleMail($content));

            $customer = Customer::where('id', auth()->user()->id)->first();

            // Send Sms To employees
            $depositNumbers=MobileNumbers::where('type','new_deposit')->get();
            foreach ($depositNumbers as $number) {
                (new smsService())->deposit($number->number, $customer, $balance);
            }
            // send Sms To Customer
            (new smsService())->newDepositCustomer($customer->phone, $balance);


            return redirect()->back()->with('success', 'Payment request received! We will confirm within 24 hours.');
        } catch (\Exception $e) {
            // Log the error for troubleshooting
            \Log::error('Email sending error: '.$e->getMessage());


            return redirect()->back();
        }
    }

    /**
     * Customer/dealer pays for a particular car from General balance
     */
    public function setCarAmount(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'amount' => 'required|numeric|min:1',
        ]);


        $car = car::find($request->car_id);

//        $accruedPercent = round((new CreditService())->totalAccruedInterestTillToday($car->id));
        $accruedPercent = round((new CreditService())->totalInterestFromLastCalc($car->id));

//        dd(round($car->amount_due + $accruedPercent) < $request->amount);

        if (round($car->amount_due + $accruedPercent) < $request->amount) {
            return back()->with('error', 'You can not pay more than amount due');
        }


        $deposit = CustomerBalance::where('customer_id', auth()->user()->id)
            ->where('is_approved', 1)
            ->sum('amount');

        if ($deposit > $request->amount) {
            $balance                  = new CustomerBalance;
            $balance->amount          = -$request->amount;
            $balance->is_approved     = true;
            $balance->type            = 'car_payment';
            $balance->customer_id     = auth()->user()->id;
            $balance->car_id          = $request->car_id;
            $balance->carpayment_date = Carbon::now();
            $balance->date            = Carbon::now();
            $balance->save();

            $car->save();

            // if credit was granted also apply the credit payment

            $newCredit = (new CreditService())->creditPayment($car, $request, $balance);
            (new CreditService())->reCalculateOnDeleteOrAdd($car, $newCredit);

            $content = [
                'dealer_name'  => auth()->user()->contact_name,
                'dealer_email' => auth()->user()->email,
                'car_model'    => $car->make,
                'car_vin'      => $car->vin,
                'payed'        => $request->amount,
            ];

            Mail::to(config('carbiddata.email'))->send(new \App\Mail\paymentReport($content));

            return back();
        }

        return back()->with('error', 'Not enough deposit');
    }

    public function updateBalance(Request $request)
    {
        $balance = CustomerBalance::find($request->balance_id);

        $request->validate([
            'bank_payment' => 'required|numeric|min:1',
            'full_name'    => 'required|string|max:255',
        ]);

        $balance->amount            = $request->bank_payment;
        $balance->full_name         = $request->full_name;
        $balance->balance_fill_date = $request->transfer_date;
        $balance->date              = $request->transfer_date;
        $balance->type              = 'fill';
        $balance->comment           = $request->comment;
        $balance->customer_id       = $request->customerID;
        $balance->save();

        return back();
    }

    public function deleteBalance(Request $request)
    {
        $payment = CustomerBalance::find($request->id);
        $payment->delete();

        return back();
    }

    /**
     * Dynamic Search with HTMX when creating customer Balance payment in Admin dashboard
     */

    public function searchCustomerHtmx(Request $request)
    {
        if (!empty($request->search)) {
            $customers = Customer::with('balances')
                ->select('customers.*')
                ->selectRaw('(SELECT SUM(amount) FROM customer_balances WHERE customer_balances.customer_id = customers.id) as deposit')
                ->where('contact_name', 'like', '%'.$request->search.'%')
                ->orWhere('company_name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')
                ->get();
        } else {
            $customers = collect();
        }

        // When search is used for update modal
        if ($request->has('index')) {
            $index = $request->index;

            return view('pages.htmx.htmxSearchCustomer', compact('customers', 'index'));
        }

        return view('pages.htmx.htmxSearchCustomer', compact('customers'));
    }


    // ============ Car Payments by Admin ===============

    public function carPaymentIndex()
    {
        $payment_reports = CustomerBalance::with(['car', 'customer'])
            ->where('type', 'car_payment')
            ->orderBy('created_at', 'desc')->get(); // Adjust the query to suit your admin access logic
        $cars            = Car::all();
        $customers       = Customer::all();
        $deposit         = CustomerBalance::where('is_approved', 1)->sum('amount');

        return view('pages.payment-report.index', compact('payment_reports', 'cars', 'customers', 'deposit'));
    }

    /**
     *  payment for particular cars from overall general balance (by admin)
     */
    public function carPaymentStore(Request $request)
    {
        $request->validate([
            'amount'       => 'required|numeric|min:1',
            'payment_date' => 'required',
        ]);

        $car = Car::where('customer_id', $request->customer_id)
            ->where('id', $request->car_id)
            ->first();

        $deposit = CustomerBalance::where('customer_id', $request->customer_id)
            ->where('is_approved', 1)
            ->sum('amount');

        if ($deposit < $request->amount) {
            return back()->with('error', 'Not enough deposit');
        }

        $accruedPercent = round((new CreditService())->totalAccruedInterestTillToday($car->id));

        if ($car->amount_due + $accruedPercent < $request->amount) {
            return back()->with('error', 'You can not pay more than amount due');
        }


        $balance              = new CustomerBalance();
        $balance->customer_id = $request->customer_id;
        $balance->car_id      = $request->car_id;
        // negative because fill and deduction has same column in database
        $balance->amount          = -$request->amount;
        $balance->carpayment_date = $request->payment_date;
        $balance->date            = $request->payment_date;
        $balance->comment         = $request->comment;
        $balance->type            = 'car_payment';
        $balance->is_approved     = 1;
        $balance->save();


        // if credit was granted also apply the credit payment
        $newCredit = (new CreditService())->creditPayment($car, $request, $balance);

        (new CreditService())->reCalculateOnDeleteOrAdd($car);


        return back();
    }

    public function carPaymentUpdate(Request $request)
    {
        $request->validate([
            'amount'       => 'required|numeric|min:1',
            'payment_date' => 'required',
        ]);

        $car = Car::where('customer_id', $request->customer_id)
            ->where('id', $request->car_id)
            ->first();

        $payment = CustomerBalance::find($request->payment_id);

        $deposit = CustomerBalance::where('customer_id', $request->customer_id)
            ->where('is_approved', 1)
            ->sum('amount');

        if ($deposit < $request->amount) {
            return back()->with('error', 'Not enough deposit');
        }


        $accruedPercent = round((new CreditService())->totalAccruedInterestTillToday($car->id), 2);

        if ($car->amount_due + $accruedPercent < $request->amount && $payment->amount * -1 < $request->amount) {
            return back()->with('error', 'You can not pay more than amount due');
        }

        (new CreditService())->reCalculateOnPaymentUpdate($car, $payment, $request->amount, $request->comment,
            $request->payment_date);


        return back();
    }

    public function carPaymentDelete(Request $request)
    {
        $payment = CustomerBalance::find($request->id);
        $car     = Car::where('id', $payment->car_id)->first();

        $car->save();
        $payment->delete();

        (new CreditService())->reCalculateOnDeleteOrAdd($car);

        return back();
    }

    /**
     * Dynamic Search with HTMX when creating Payment for Cars in Admin dashboard
     */
    public function carSearchHtmx(Request $request)
    {
        if (!empty($request->search)) {
            $cars = Car::where('amount_due', '>', 0)
                ->where('customer_id', $request->customer_id)
                ->where(function ($query) use ($request) {
                    $query
                        ->where('vin', 'like', '%'.$request->search.'%')
                        ->orWhere('make_model_year', 'like', '%'.$request->search.'%');
                })
                ->with([
                    'credit' => function ($query) {
                        $query
                            ->orderBy('issue_or_payment_date', 'asc')
                            ->selectRaw('car_id, SUM(accrued_percent) as total_accrued_percent')
                            ->groupBy('car_id');
                    },
                ])->get();
        } else {
            $cars = collect();
        }

        if ($request->has('index')) {
            $index = $request->index;

            return view('pages.htmx.htmxSearchCar', compact('cars', 'index'));
        }


        return view('pages.htmx.htmxSearchCar', compact('cars'));
    }

    public function percentTillDateHtmx(Request $request)
    {
//       $totaldue =
        $total_percent = (new CreditService())->totalAccruedInterestTillToday($request->car_id2,
            $request->payment_date);
        $total_due     = $request->due2 + $total_percent;

        return view('pages.htmx.htmxPercentTillDate', compact('total_percent', 'total_due'));
    }


}
