<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Models\Car;
use App\Models\Customer;
use App\Models\CustomerBalance;
use App\Models\Extraexpence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{

    public function adminIndex(){

        $totalCustomers = Customer::where('is_active', 1)->count();
        $totalCars = Car::where('is_active', 1)->count();
        $totalCarsPaid = Car::where('amount_due', 0)->count();
        $totalCarsDue = Car::where('amount_due', '!=', 0)->count();


        $totalDeposits =  number_format(CustomerBalance::where('type', 'fill')->sum('amount'), 0, '.', ',');
        $totalAmountDue = number_format(Car::where('amount_due', '!=', 0)->sum('amount_due'), 0, '.', ',');
        $totalSpent = number_format(CustomerBalance::where('type', 'car_payment')->sum('amount')*-1, 0, '.', ',');  ;


        return view('dashboard', compact('totalCustomers', 'totalCars', 'totalCarsPaid', 'totalCarsDue', 'totalDeposits', 'totalAmountDue', 'totalSpent'));
    }

    public function customerIndex(Request $request)
    {
        $perpage = $request->input('perpage', 50);
        $extraexpences=Extraexpence::all();

        if ($request->has('auction') && $request->auction === 'all') {
            return to_route('locations.index');
        }

        if ($request->has('search')) {
            $search    = $request->input('search');

            if ($request->has('archive')){
                $customers = Customer::onlyTrashed()->with('cars.latestCredit', 'cars.credit', 'balances')
                    ->where(function ($query) use ($search) {
                        $query->where('contact_name', 'like', '%' . $search . '%')
                            ->orWhere('company_name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                    })
                    ->paginate($perpage)
                    ->withQueryString();
            } else{

                $customers = Customer::with('cars.latestCredit', 'cars.credit', 'balances')
                ->where('contact_name', 'like', '%'.$search.'%')
                    ->orWhere('company_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->paginate($perpage)->withQueryString();
            }

            $count        = $customers->total();

            return view('pages.customers', compact('customers', 'count','extraexpences'));
        }




        if ($request->has('archive')){

            $customers    = Customer::onlyTrashed()
                ->with('cars.latestCredit', 'cars.credit', 'balances')
                ->paginate($perpage)->withQueryString();

        } else {

            $customers    = Customer::with('cars.latestCredit', 'cars.credit', 'balances')
            ->paginate($perpage)->withQueryString();

        }

        $trashed      = Customer::onlyTrashed()->paginate($perpage)->withQueryString();
        $count        = $customers->total();
        $trashedCount = $trashed->total();


        return view('pages.customers', compact('extraexpences','customers', 'count', 'trashed', 'trashedCount'));
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

    public function restore($id){

        $customer = Customer::onlyTrashed()->find($id);
        if ($customer) {
            $customer->restore();
            return back();
        }
        return back()->with('error', 'Customer not found');
    }

    public function update(Request $request)
    {
        $customer = Customer::find($request->id);
        $extraexpenses=Extraexpence::all();



        $request->validate([
            'number_of_cars' => 'required', // Add more validation rules as needed
            'contact_name'   => 'required',
            'phone'          => 'required',
        ]);


        if ($customer->child_of>0) {
            $customer->company_name    = '  ';
        } else {
            $customer->company_name    = $request->input('company_name');
        }

        $customer->contact_name    = $request->input('contact_name');
        $customer->personal_number = $request->input('personal_number');
        $customer->extra_for_team = isset($request->extra_for_team) ? $request->extra_for_team : 0;
        $customer->phone          = $request->input('phone');
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
        $customer->is_active      = 1;


        $extraExpenseArray=[];
        $extraExpenseArray[]=['name' => '', 'value' => 0,'date'=>''];
        foreach ($extraexpenses as $extraexpense) {

            if($request->has($extraexpense->name)) {
                $request->validate([
                    $extraexpense->name => 'nullable|numeric',
                ]);
                if ($request->input($extraexpense->name) > 0) {
                    $extraExpenseArray[] = [
                        'name' => $extraexpense->name,
                        'value' => $request->input($extraexpense->name),
                    ];
                }

            }
        }

        $customer->extra_expenses = json_encode($extraExpenseArray);

        $customer->save();


        return back();
    }

    public function autoLogin(Request $request){

        $customer=Customer::where('id',$request->customer_id)->first();

        Auth::guard('customer')->login($customer);

        Session::put('auth', $customer);


        return to_route('customer.dashboard');

    }

}
