<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function customerIndex(Request $request)
    {
        $perpage = $request->input('perpage', 50);

        if ($request->has('auction') && $request->auction === 'all') {
            return to_route('locations.index');
        }

        if ($request->has('search')) {
            $search    = $request->input('search');
            $customers = Customer::where('contact_name', 'like', '%'.$search.'%')
                ->orWhere('company_name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%')
                ->orWhere('phone', 'like', '%'.$search.'%')
                ->paginate($perpage)->withQueryString();

            $trashed = Customer::onlyTrashed()
                ->where(function ($query) use ($search) {
                    $query->where('contact_name', 'like', '%'.$search.'%')
                        ->orWhere('company_name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('phone', 'like', '%'.$search.'%');
                })
                ->paginate($perpage)
                ->withQueryString();

            $count        = $customers->total();

            $trashedCount = $trashed->total();

            return view('pages.customers', compact('customers', 'count', 'trashed', 'trashedCount'));
        }

        $customers    = Customer::paginate($perpage)->withQueryString();
        $trashed      = Customer::onlyTrashed()->paginate($perpage)->withQueryString();
//        dd($trashed);
        $count        = $customers->total();
        $trashedCount = $trashed->total();


        return view('pages.customers', compact('customers', 'customers', 'count', 'trashed', 'trashedCount'));
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
        $customer = Customer::find($request->id);
        $customer->delete();

        return back();
    }

    public function update(Request $request)
    {
        $customer = Customer::find($request->id);

//        dd($request->email !== $customer->email);

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

        $customer->save();


        return back();
    }

}
