<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Couchbase\Role;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index(Request $request){
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
}
