<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Extraexpence;
use Illuminate\Http\Request;

class ExtraexpenceController extends Controller
{
    public function htmxGetExtraExpense(Request $request){
        $selectedcustomer=Customer::where('id', $request->customer_id)->first();
        $extra_expenses=Extraexpence::all();

        return view('pages.htmx.htmxExtraExpenseCustomer', compact('selectedcustomer','extra_expenses'));
    }
}
