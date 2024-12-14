<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function customerIndex(Request $request){

        $perpage=$request->input('perpage', 10);

        if ($request->has('auction') && $request->auction === 'all') {
            return to_route('locations.index');
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $customers = Customer::
            where('name', 'like', '%' . $search . '%')
                ->paginate($perpage)->withQueryString();
            $count=$customers->total();
            return view('pages.locations', compact('customers','count'));
        }

        $customers = Customer::paginate($perpage)->withQueryString();
        $count=$customers->total();

        return view('pages.customers', compact('customers','customers','count'));
    }

    public function customerActivate(Request $request){

        $validate=$request->validate([
            'id'=>'required|exists:customers,id',
        ]);


        $customer=Customer::find($request->id);
        if (!$customer) {
            return back()->with('error', 'Customer not found');
        }

        if ($customer->is_active==1) {
            $customer->is_active=0;
            $customer->save();
            return back();

        } else {
            $customer->is_active=1;
            $customer->save();
            return back();
        }
    }
}