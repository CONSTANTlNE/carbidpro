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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CustomerBalanceController extends Controller
{

    // ================ Balance Fills ====================

    public function index(Request $request)
    {

        $trashedCount=CustomerBalance::onlyTrashed()->count();


        if ($request->has('search') && !empty($request->search) && $request->archived=='true') {
            $payment_requests = CustomerBalance::with(['customer.cars', 'media'])
                ->onlyTrashed() // Ensure we're only dealing with trashed CustomerBalance records
                ->where('type', 'fill')
                ->where(function ($query) use ($request) {
                    $query
                        ->where('full_name', 'like', '%'.$request->search.'%')
                        ->orWhere('amount', 'like', '%'.$request->search.'%')
                        ->orWhere('created_at', 'like', '%'.$request->search.'%');
                })
                ->where(function ($query) use ($request) {
                    $query->whereHas('customer', function ($query) use ($request) {
                        $query
                            ->where('company_name', 'like', '%'.$request->search.'%')
                            ->orWhere('contact_name', 'like', '%'.$request->search.'%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(50)
                ->withQueryString();






            return view('pages.balance_fills.index', compact('payment_requests','trashedCount'));
        }


        if ($request->archived=='true') {
            $payment_requests = CustomerBalance::onlyTrashed()->with(['customer.cars', 'media'])
                ->where('type', 'fill')
                ->orderBy('created_at', 'desc')
                ->paginate(50)
                ->withQueryString();
            $archive=true;

            return view('pages.balance_fills.index', compact('payment_requests', 'archive','trashedCount'));
        }




        if ($request->has('search') && !empty($request->search)) {
            $payment_requests = CustomerBalance::with(['customer.cars', 'media'])
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


            return view('pages.balance_fills.index', compact('payment_requests','trashedCount'));
        }




        $payment_requests = CustomerBalance::with(['customer.cars', 'media'])
            ->where('type', 'fill')
            ->orderBy('created_at', 'desc')
            ->paginate(50)
            ->withQueryString();

        return view('pages.balance_fills.index', compact('payment_requests','trashedCount'));
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
//        dd($request);
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


        $customer = Customer::where('id', auth()->user()->id)->first();

        // Send Sms To employees
        $depositNumbers = MobileNumbers::where('type', 'new_deposit')->get();
        foreach ($depositNumbers as $number) {
            (new smsService())->deposit($number->number, $customer, $balance);
        }
        // send Sms To Customer
        (new smsService())->newDepositCustomer($customer->phone, $balance);

        try {
            $content = [
                'company_name' => auth()->user()->company_name,
                'dealer_name'  => auth()->user()->contact_name,
                'name'         => $balance->full_name,
                'date'         => $balance->date,
                'amount'       => $balance->amount,
            ];

            Mail::to(config('carbiddata.email'))->send(new SampleMail($content));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }


        return redirect()->back()->with('success', 'Payment request received! We will confirm within 24 hours.');
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

        if ($car->latestCredit) {
            if (round($car->amount_due)+(new creditService())->totalAccruedInterestTillToday($car) < $request->amount) {
                return back()->with('error', 'You can not pay more than amount due');
            }
        } else {
            if (round($car->amount_due) < $request->amount) {
                return back()->with('error', 'You can not pay more than amount due');
            }
        }


        $deposit = CustomerBalance::where('customer_id', auth()->user()->id)
            ->where('is_approved', 1)
            ->sum('amount');

        if ($deposit >= $request->amount) {
            $balance                  = new CustomerBalance;
            $balance->amount          = -$request->amount;
            $balance->is_approved     = true;
            $balance->type            = 'car_payment';
            $balance->customer_id     = auth()->user()->id;
            $balance->car_id          = $request->car_id;
            $balance->carpayment_date = Carbon::now();
            $balance->date            = Carbon::now();
            $balance->save();

            // If no Credit Given deduct from amount due
            if ($car->credit->isEmpty()) {
                $car->amount_due = $car->amount_due - $request->amount;
            }

            $car->save();


            if ($car->latestCredit) {
                (new CreditService())->recalc($car);
            }


            $content = [
                'dealer_name'  => auth()->user()->contact_name,
                'dealer_email' => auth()->user()->email,
                'car_model'    => $car->make_model_year,
                'car_vin'      => $car->vin,
                'payed'        => $request->amount,
            ];

            Mail::to(config('carbiddata.email'))->send(new \App\Mail\paymentReport($content));

            return back()->with('success', 'Amount paid for '.$car->vin);
        }

        return back()->with('error', 'Not enough deposit');
    }

    public
    function updateBalance(
        Request $request,
    ) {
        $balance = CustomerBalance::find($request->balance_id);

        $request->validate([
            'bank_payment' => 'required|numeric|min:1',
            'full_name'    => 'required|string|max:255',
            'comment'      => 'nullable|string',
        ]);

        $balance->amount            = $request->bank_payment;
        $balance->full_name         = $request->full_name;
        $balance->balance_fill_date = $request->transfer_date;
        $balance->date              = $request->transfer_date;
        $balance->type              = 'fill';
        $balance->comment           = $request->comment;
        $balance->save();


        if ($request->hasFile('paymentorder')) {
            $balance->media->first()?->delete();

            $balance->addMediaFromRequest('paymentorder')->toMediaCollection('paymentorder');
        }


        return back();
    }

    public function deleteBalance(
        Request $request,
    ) {

//        dd($request->all());

        if ($request->archived == 1) {
            $payment = CustomerBalance::withTrashed()->find($request->id);

            if (!$payment) {
                return back()->with('error', 'Deposit not found');
            }

            $payment->forceDelete();
            return back()->with('success', 'Archived deposit permanently deleted');
        }


        $payment = CustomerBalance::find($request->id);

        if (!$payment) {
            return back()->with('error', 'Deposit not found');
        }

        // Check if the deposit can be deleted (only for approved deposits)
        if ($payment->is_approved === 1) {
            $currentDeposit = CustomerBalance::where('customer_id', $payment->customer_id)
                ->where('is_approved', 1)
                ->sum('amount');

            if ($payment->amount > $currentDeposit) {
                return back()->with('error',
                    'Requested amount '.$payment->amount.' is already spent on cars, maximum amount to be deleted is '.$currentDeposit);
            }
        }

        // Perform the deletion
        $payment->delete();
        return back()->with('success', 'Deposit successfully deleted');
    }

    /**
     * Dynamic Search with HTMX when creating customer Balance payment in Admin dashboard
     */

    public function searchCustomerHtmx(
        Request $request,
    ) {
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

    public
    function carPaymentIndex(
        Request $request,
    ) {
        if ($request->has('search') && !empty($request->search)) {
            $payment_reports = CustomerBalance::with([
                'car' => function ($query) {
                    $query->withTrashed();
                }, 'customer',
            ])
                ->where('type', 'car_payment')
                ->where(function ($query) use ($request) {
                    $query
                        ->where('full_name', 'like', '%'.$request->search.'%')
                        ->orWhere('amount', 'like', '%'.$request->search.'%')
                        ->orWhereHas('car', function ($carQuery) use ($request) {
                            $carQuery
                                ->where('vin', 'like', '%'.$request->search.'%')
                                ->orWhere('make_model_year', 'like', '%'.$request->search.'%');
                        });
                })
                ->orderBy('created_at', 'desc')
                ->get(); // Ensure you call ->get() here to execute the query.
        } else {
            $payment_reports = CustomerBalance::with([
                'car' => function ($query) {
                    $query->withTrashed();
                }, 'customer',
            ])
                ->where('type', 'car_payment')
                ->orderBy('created_at', 'desc')
                ->get();
        }


        $cars      = Car::all();
        $customers = Customer::all();
        $deposit   = CustomerBalance::where('is_approved', 1)->sum('amount');

        return view('pages.payment-report.index', compact('payment_reports', 'cars', 'customers', 'deposit'));
    }

    /**
     *  payment for particular cars from overall general balance (by admin)
     */
    public function carPaymentStore(
        Request $request,
    ) {
        $request->validate([
            'amount'       => 'required|numeric|min:1',
            'payment_date' => 'required',
        ]);

        $car = Car::where('customer_id', $request->customer_id)
            ->where('id', $request->car_id)
            ->first();


        if ($car->latestCredit) {
            if (round($car->amount_due)+(new creditService())->totalAccruedInterestTillToday($car) < $request->amount) {
                return back()->with('error', 'You can not pay more than amount due');
            }
        } else {
            if (round($car->amount_due) < $request->amount) {
                return back()->with('error', 'You can not pay more than amount due');
            }
        }

        $deposit = CustomerBalance::where('customer_id', $request->customer_id)
            ->where('is_approved', 1)
            ->sum('amount');

        if ($deposit >= $request->amount) {
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

            // If no Credit Given deduct from amount due

            if ($car->credit->isEmpty()) {
                $car->amount_due = $car->amount_due - $request->amount;
                $car->save();
            }

            // if credit was granted also apply the credit payment
//            (new CreditService())->creditPayment($car, $request, $balance);

            if ($car->latestCredit) {
                (new CreditService())->recalc($car);
            }


            return back();
        }

        return back()->with('error', 'Not enough deposit');
    }

    public function carPaymentUpdate(
        Request $request,
    ) {
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
                ->sum('amount') + ($payment->amount * -1);


        if ($deposit < $request->amount && $payment->amount * -1 < $request->amount) {
            return back()->with('error', 'Not enough deposit');
        }


        $accruedPercent = round((new CreditService())->totalAccruedInterestTillToday($car), 2);

        if ($car->amount_due + $accruedPercent < $request->amount && $payment->amount * -1 < $request->amount) {
            return back()->with('error', 'You can not pay more than amount due');
        }


        $payment->carpayment_date = $request->payment_date;
        $payment->date            = $request->payment_date;
        $payment->comment         = $request->comment;
        $payment->amount          = -$request->amount;
        $payment->save();


        if ($car->latestCredit) {
            (new CreditService())->recalc($car);
        } else {
            $totalPayments = CustomerBalance::where('car_id', $car->id)
                ->where('type', 'car_payment')
                ->sum('amount');

            $car->amount_due = $car->total_cost + $totalPayments;
            $car->save();
        }


        return back();
    }

    public function carPaymentDelete(Request $request)
    {
        $payment = CustomerBalance::find($request->id);
        $car     = Car::where('id', $payment->car_id)->first();




        if (!$car->latestCredit) {
            $car->amount_due = $car->amount_due + ($payment->amount * -1);
        }

        $car->save();
        $payment->delete();

        if ($car->latestCredit) {
            $creditrecord = Credit::where('customer_balance_id', $payment->id)->first();

            if ($creditrecord) {
                $creditrecord->delete();
            }
            (new CreditService())->recalc($car);
        }


        return back();
    }

    /**
     * Dynamic Search with HTMX when creating Payment for Cars in Admin dashboard
     */
    public function carSearchHtmx(
        Request $request,
    ) {
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
                            ->selectRaw('car_id, MAX(issue_or_payment_date) as latest_issue_date, SUM(accrued_percent) as total_accrued_percent')
                            ->groupBy('car_id')
                            ->orderBy('latest_issue_date', 'asc');
                    }, 'latestCredit',
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

    public
    function percentTillDateHtmx(
        Request $request,
    ) {
//       $totaldue =
        $total_percent = (new CreditService())->totalAccruedInterestTillToday($request->car_id2,
            $request->payment_date);
        $total_due     = $request->due2 + $total_percent;

        return view('pages.htmx.htmxPercentTillDate', compact('total_percent', 'total_due'));
    }


    public
    function transferBalanceToOld(
        Request $request,
    ) {
        $request->validate([
            'amount'      => 'required|numeric|min:1',
            'customer_id' => 'nullable|numeric|exists:customers,id',
        ]);

        $deposit = CustomerBalance::where('customer_id', $request->customer_id)
            ->where('is_approved', 1)
            ->sum('amount');

        if ($deposit < $request->amount) {
            return back()->with('error', 'Not enough deposit');
        }


        if ($request->customer_id) {
            $customer_id = $request->customer_id;
        } else {
            $customer_id = auth()->user()->id;
        }

        $transferAmount              = new CustomerBalance();
        $transferAmount->customer_id = $customer_id;
        $transferAmount->amount      = -$request->amount;
        $transferAmount->date        = now();
        $transferAmount->type        = 'transfer_to_old_account';
        $transferAmount->is_approved = 1;
        $transferAmount->save();

        return back();
    }

    public function restoreArchived(Request $request){

        $deposit=CustomerBalance::withTrashed()->find($request->id);


        if ($deposit) {
            $deposit->restore();
            return to_route('customer.balance.index');
        }

        return back()->with('error','Deposit not found');

    }
}
