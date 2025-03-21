<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Models\Car;
use App\Models\Credit;
use App\Models\Customer;
use App\Models\CustomerBalance;
use App\Models\Extraexpence;
use App\Models\Insurance;
use App\Models\Title;
use App\Services\CreditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AdminController extends Controller
{

    public function adminIndex()
    {

        $totalCustomers = Customer::where('is_active', 1)->count();
        $totalCars      = Car::where('is_active', 1)->count();
        $totalCarsPaid  = Car::where('amount_due', 0)->count();
        $totalCarsDue   = Car::where('amount_due', '!=', 0)->count();



        $cars = Car::with('latestCredit','credit')->get();


        $totalAmountDue = 0;

        foreach ($cars as $car) {

            if ($car->amount_due != 0) {
                $creditExcludedCost = 0;
                foreach (json_decode($car->balance_accounting) as $cost) {
                    if ($cost->forcredit == 0) {
                        $creditExcludedCost += $cost->value;
                    }
                }

                if($car->latestCredit?->credit_amount>0) {
                    if ($car->latestCredit->credit_amount < $creditExcludedCost) {
                        $totalAmountDue += round( $car->amount_due);
                    }else{
                        $totalAmountDue += round($car->latestCredit->credit_amount + (new CreditService)->totalInterestFromLastCalc($car)) + $creditExcludedCost;
                    }
                } else {
                    $totalAmountDue += round($car->amount_due);
                }
            }
        }

        $totalShipping=0;
        $totalInterest=0;
        foreach ($cars as $car) {
            foreach (json_decode($car->balance_accounting) as $cost) {
                if ($cost->name == 'Shipping cost') {
                    $totalShipping += $cost->value;
                }
            }
            $totalInterest += (new CreditService())->totalAccruedInterestTillToday($car);
        }

        $totalDeposits = number_format(CustomerBalance::where('type', 'fill')->sum('amount'), 0, '.', ',');
//        $totalAmountDue = number_format(Car::where('amount_due', '!=', 0)->sum('amount_due'), 0, '.', ',');
        $totalSpent = number_format(CustomerBalance::where('type', 'car_payment')->sum('amount') * -1, 0, '.', ',');





       // Total Issued Credit
        $credits = DB::table('credits as c1')
            ->join(DB::raw('(SELECT car_id, MIN(issue_or_payment_date) as first_date 
                     FROM credits GROUP BY car_id) as c2'),
                'c1.car_id', '=', 'c2.car_id')
            ->whereColumn('c1.issue_or_payment_date', 'c2.first_date')
            ->select('c1.*') // Select all columns from the credits table (including credit_amount)
            ->get();

        $filteredCredits = $credits->groupBy('car_id')->map(function ($group) {
            return $group->sortByDesc('credit_amount')->sortBy('issue_or_payment_date')->first();
        });
        $totalCreditAmount = $filteredCredits->sum('credit_amount');



        return view('dashboard',
            compact('totalCustomers', 'totalCars', 'totalCarsPaid', 'totalCarsDue', 'totalDeposits', 'totalAmountDue',
                'totalSpent', 'totalShipping','totalInterest','totalCreditAmount'));
    }

    public function customerIndex(Request $request)
    {
        $perpage       = $request->input('perpage', 50);
        $extraexpences = Extraexpence::all();

//        dd(cache::get('titles'));


        if ($request->has('auction') && $request->auction === 'all') {
            return to_route('locations.index');
        }

        if ($request->has('search')) {
            $search = $request->input('search');

            if ($request->has('archive')) {
                $customers = Customer::onlyTrashed()->with('cars.latestCredit', 'cars.credit', 'balances')
                    ->where(function ($query) use ($search) {
                        $query
                            ->where('contact_name', 'like', '%'.$search.'%')
                            ->orWhere('company_name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%')
                            ->orWhere('phone', 'like', '%'.$search.'%');
                    })
                    ->paginate($perpage)
                    ->withQueryString();
            } else {
                $customers = Customer::with('cars.latestCredit', 'cars.credit', 'balances')
                    ->where('contact_name', 'like', '%'.$search.'%')
                    ->orWhere('company_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->paginate($perpage)->withQueryString();
            }

            $count = $customers->total();

            return view('pages.customers', compact('customers', 'count', 'extraexpences'));
        }


        if ($request->has('archive')) {
            $customers = Customer::onlyTrashed()
                ->with('cars.latestCredit', 'cars.credit', 'balances')
                ->paginate($perpage)->withQueryString();
        } else {
            $customers = Customer::with('cars.latestCredit', 'cars.credit', 'balances')
                ->paginate($perpage)->withQueryString();
        }

        $trashed      = Customer::onlyTrashed()->paginate($perpage)->withQueryString();
        $count        = $customers->total();
        $trashedCount = $trashed->total();


        return view('pages.customers', compact('extraexpences', 'customers', 'count', 'trashed', 'trashedCount'));
    }

    public function customerActivate(Request $request)
    {
        $validate = $request->validate([
            'id' => 'required|exists:customers,id',
        ]);

        $customer = Customer::find($request->id);

        if (!$customer) {
            return back()->with('error', 'Customer not found');
        }

        if ($customer->is_active == 1) {
            $customer->is_active = 0;
            $customer->save();

            return back();
        } else {
            $customer->is_active = 1;
            $customer->save();

            return back();
        }
    }

    public function delete(Request $request)
    {
        $customer = Customer::withTrashed()->find($request->id);

        if ($customer) {
            if ($customer->trashed()) {
                $customer->forceDelete();
            } else {
                $customer->delete();
            }

            return back();
        }

        return back()->with('error', 'Customer not found');
    }

    public function restore($id)
    {
        $customer = Customer::onlyTrashed()->find($id);
        if ($customer) {
            $customer->restore();

            return back();
        }

        return back()->with('error', 'Customer not found');
    }

    public function update(Request $request)
    {
        $customer      = Customer::find($request->id);
        $extraexpenses = Extraexpence::all();
//       dd($request->all());


        $request->validate([
            'number_of_cars' => 'required', // Add more validation rules as needed
            'contact_name'   => 'required',
            'phone'          => 'required',
        ]);


        if ($customer->child_of > 0) {
            $customer->company_name = '  ';
        } else {
            $customer->company_name = $request->input('company_name');
        }

        $customer->contact_name    = $request->input('contact_name');
        $customer->personal_number = $request->input('personal_number');
        $customer->extra_for_team  = isset($request->extra_for_team) ? $request->extra_for_team : 0;
        $customer->phone           = $request->input('phone');
        if ($request->email !== $customer->email) {
            $request->validate([
                'email' => 'required|email|unique:customers',
            ]);
            $customer->email = $request->input('email');
        }
        if (isset($request->password)) {
            $customer->password          = Hash::make($request->input('password'));
            $customer->unhashed_password = $request->input('password');
        }
        $customer->number_of_cars = $request->input('number_of_cars');
        $customer->comment        = $request->input('comment');


        $extraExpenseArray   = [];
        $extraExpenseArray[] = ['name' => '', 'value' => 0, 'date' => '','id' => 0,'forcredit' => 1];

        foreach ($extraexpenses as $extraexpense) {
//          if ($extraexpense->id===8){
//             dd( $request->has(str_replace(' ', '_', $extraexpense->name)));
//          }
            if ($request->has(str_replace(' ', '_', $extraexpense->name))) {
                $request->validate([

                    str_replace(' ', '_', $extraexpense->name) => 'nullable|numeric',

                ]);
                if ($request->input( str_replace(' ', '_', $extraexpense->name) ) > 0) {
                    $extraExpenseArray[] = [
                        'name'  => $extraexpense->name,
                        'value' => $request->input(str_replace(' ', '_', $extraexpense->name)),
                    ];
                }
            }
        }

        $customer->extra_expenses = json_encode($extraExpenseArray);

        $customer->save();


        return back();
    }

    public function autoLogin(Request $request)
    {
        $customer = Customer::where('id', $request->customer_id)->first();

        Auth::guard('customer')->login($customer);

        Session::put('auth', $customer);


        return to_route('customer.dashboard');
    }

    public function customerTitles(Request $request)
    {
        $customer = Customer::with('titles')->find($request->id);

        if (cache::get('titles')) {
            $titles = cache::get('titles');
        } else {
            $titles = Title::all();
            cache::forever('titles', $titles);
        }


        return view('pages.htmx.customerTitles', compact('customer', 'titles'));
    }

    public function addCustomerTitle(Request $request)
    {
        $customer = Customer::find($request->id);

        $request->validate([
            'title_id'           => 'required',
            'title_for_customer' => 'required',
        ]);

        if ($customer) {
            $customer->titles()->attach($request->title_id, ['title_for_customer' => $request->title_for_customer]);

            return back();
        }

        return back()->with('error', 'Customer not found');
    }

    public function updateCustomerTitle(Request $request)
    {
        $request->validate([
            'title_id'           => 'required',
            'title_for_customer' => 'required',
        ]);

        $customer = Customer::find($request->id);
        if ($customer) {
            $customer->titles()->updateExistingPivot($request->title_id,
                ['title_for_customer' => $request->title_for_customer]);
        }

        return response()->noContent();
    }

    public function deleteCustomerTitle(Request $request)
    {
        $request->validate([
            'title_id' => 'required',
        ]);

        $customer = Customer::find($request->id);

        $customer->titles()->detach($request->title_id);

        return back();
    }

    public function insuranceIndex(){

        $insuranceterms=Insurance::all();

       return view('pages.insruranceterms',compact('insuranceterms'));
    }

    public function insuranceStore(Request $request){

        $insurance=new Insurance();
        $insurance->text=$request->insurancetext;
        $insurance->save();

        return back();

    }

    public function insuranceUpdateHtmx(Request $request){

        $insurance = Insurance::where('id', $request->id)->first();
        $id = $request->id;

        return view('pages.htmx.htmxUpdateInsurance', compact('insurance', 'id'));
    }

    public function insuranceUpdate(Request $request){

        $insurance=Insurance::where('id',$request->id)->first();
        $insurance->text=$request->insurancetext;
        $insurance->save();

        return back();


    }

    public function insuranceDelete(Request $request){

    }

    public function showInsurance(){

        $insurance=Insurance::first();



        return view('frontend.pages.insurance',compact('insurance'));
    }

}
