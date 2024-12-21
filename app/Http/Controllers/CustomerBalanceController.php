<?php

namespace App\Http\Controllers;

use App\Mail\SampleMail;
use App\Models\Car;
use App\Models\Customer;
use App\Models\CustomerBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerBalanceController extends Controller
{

    // Balance Fills

    public function index()
    {
        $payment_requests = CustomerBalance::with(['customer.cars'])
            ->where('type', 'fill')
            ->orderBy('created_at',
                'desc')->get(); // Adjust the query to suit your admin access logic

        return view('pages.balance_fills.index', compact('payment_requests'));
    }

    /**
     * Approve a payment request (general amount like filling balance)
     */
    public function approveBalance(Request $request)
    {
        $prequest = CustomerBalance::where('id', $request->id)->first();

        if ($prequest->is_approved === 0) {
            $prequest->is_approved = 1;
            $prequest->save();
        } else {
            $prequest->is_approved = 0;
            $prequest->save();
        }

        return back();
    }

    /**
     * General amount transfer (like filling balance)
     */
    public function registrPaymentRequest(Request $request)
    {
        $request->validate([
            'bank_payment' => 'required|numeric|min:1',
            'full_name'    => 'required|string|max:255',
        ]);

        $balance = new CustomerBalance;

        $balance->amount    = $request->bank_payment;
        $balance->full_name = $request->full_name;
//        $balance->date        = $request->payment_date;
        $balance->type        = 'fill';
        $balance->customer_id = auth()->user()->id;
        $balance->save();


        try {
            $content = [
                'name'   => $balance->full_name,
                'date'   => $balance->date,
                'amount' => $balance->amount,
            ];


            Mail::to(config('carbiddata.email'))->send(new SampleMail($content));


            // foreach (['First Coder' => 'first-recipient@gmail.com', 'Second Coder' => 'second-recipient@gmail.com'] as $name => $recipient) {
            //     Mail::to($recipient)->send(new MyTestEmail($name));
            // }

            return redirect()->back()->with('success', 'Payment request received! We will confirm within 24 hours.');
        } catch (\Exception $e) {
            // Log the error for troubleshooting
            \Log::error('Email sending error: '.$e->getMessage());


            return redirect()->back();
        }
    }

    /**
     * Dealer pays for a particular car from General balance
     */
    public function setCarAmount(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'amount' => 'required|numeric|min:1',
        ]);


        $car = car::find($request->car_id);


        if ($car->amount_due < $request->amount) {
            return back()->with('error', 'You can not pay more than amount due');
        }


        $deposit = CustomerBalance::where('customer_id', auth()->user()->id)
            ->where('is_approved', 1)
            ->sum('amount');

        if ($deposit > $request->amount) {
            $balance              = new CustomerBalance;
            $balance->amount      = -$request->amount;
            $balance->is_approved = true;
            $balance->type        = 'car_payment';
            $balance->customer_id = auth()->user()->id;
            $balance->car_id      = $request->car_id;

            $balance->save();

            $car             = car::find($request->car_id);
            $car->amount_due = $car->amount_due - $request->amount;
            $car->save();

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

    public function updateBalance(Request $request) {}

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
            $customers = Customer::where('contact_name', 'like', '%'.$request->search.'%')
                ->orWhere('company_name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')
                ->get();
        } else {
            $customers = collect();
        }

        // When search is used for update modal
        if($request->has('index')){

            $index=$request->index;
            return view('pages.htmx.htmxSearchCustomer', compact('customers', 'index'));
        }

        return view('pages.htmx.htmxSearchCustomer', compact('customers'));
    }


    // Car Payments

    public function carPaymentIndex()
    {
        $payment_reports = CustomerBalance::with(['car', 'customer'])
            ->where('type', 'car_payment')
            ->orderBy('created_at', 'desc')->get(); // Adjust the query to suit your admin access logic
        $cars            = Car::all();
        $customers       = Customer::all();

        return view('pages.payment-report.index', compact('payment_reports', 'cars', 'customers'));
    }

    public function carPaymentStore(Request $request)
    {

//        dd($request->all());
        $car = Car::where('customer_id', $request->customer_id)
            ->where('id', $request->car_id)
            ->first();

        $deposit = CustomerBalance::where('customer_id', $request->customer_id)
            ->where('is_approved', 1)
            ->sum('amount');

        if($deposit < $request->amount){
            return back()->with('error', 'Not enough deposit');
        }

        if($car->amount_due < $request->amount){
            return back()->with('error', 'You can not pay more than amount due');
        }

        $car->amount_due = $car->amount_due - $request->amount;
        $car->save();

        $carpayment = new CustomerBalance();
        $carpayment->customer_id = $request->customer_id;
        $carpayment->car_id = $request->car_id;
        $carpayment->amount = -$request->amount;
        $carpayment->type = 'car_payment';
        $carpayment->is_approved = 1;
        $carpayment->save();

        return back();

    }

    public function carPaymentUpdate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_id' => 'required|exists:customer_balances,id',
            'customer_id' => 'required|exists:customers,id',
            'car_id' => 'required|exists:cars,id',
        ]);

        $car = Car::where('id', $request->car_id)->first();
        $deposit = CustomerBalance::where('customer_id', $request->customer_id)
            ->where('is_approved', 1)
            ->sum('amount');

        if($deposit < $request->amount){
            return back()->with('error', 'Not enough deposit');
        }

        if($car->amount_due < $request->amount){
            return back()->with('error', 'You can not pay more than amount due');
        }


        $carpayment=CustomerBalance::find($request->payment_id);


        // payments for cars (deduction from general balance) are recorded with negative,
        // and amount_due in cars are positive number
        // so we need to multiply balance amount by -1

        // if previous amount was less than new amount
        if(($carpayment->amount)*-1 < $request->amount){
            $amountToBeAdded =$request->amount-($carpayment->amount)*-1;
            $car->amount_due -= $amountToBeAdded;
            $car->save();
        }

        // if previous amount was greater than new amount
        if(($carpayment->amount)*-1 > $request->amount){
            $amountToBeRemoved =($carpayment->amount)*-1-$request->amount;
            $car->amount_due += $amountToBeRemoved;
            $car->save();
        }

        $carpayment->amount=-$request->amount;
        $carpayment->customer_id=$request->customer_id;
        $carpayment->car_id=$request->car_id;
        if($request->approve==='on'){
            $carpayment->is_approved=1;
        }else{
            $carpayment->is_approved=0;
        }
        $carpayment->save();

        return back();

    }

    public function carPaymentDelete(Request $request)
    {
        $payment = CustomerBalance::find($request->id);
        $car = Car::where('id', $payment->car_id)->first();

        $car->amount_due = $car->amount_due + $payment->amount;
        $car->save();

        $payment->delete();

        return back();
    }

    /**
     * Dynamic Search with HTMX when creating Payment for Cars in Admin dashboard
     */
    public function carSearchHtmx(Request $request)
    {

        if (!empty($request->search)) {
            $cars = Car::where('customer_id', $request->customer_id)
                ->where(function ($query) use ($request) {
                    $query->where('vin', 'like', '%'.$request->search.'%')
                        ->orWhere('make_model_year', 'like', '%'.$request->search.'%');
                })
                ->get();
        } else {
            $cars = collect();
        }

        if($request->has('index')){

            $index=$request->index;
            return view('pages.htmx.htmxSearchCar', compact('cars', 'index'));
        }



        return view('pages.htmx.htmxSearchCar', compact('cars'));
    }


}
