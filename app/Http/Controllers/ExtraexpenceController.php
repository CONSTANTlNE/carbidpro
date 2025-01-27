<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Customer;
use App\Models\Extraexpence;
use Illuminate\Http\Request;

class ExtraexpenceController extends Controller
{
    public function htmxGetExtraExpense(Request $request)
    {
        $selectedcustomer = Customer::where('id', $request->customer_id)->first();

        if ($selectedcustomer) {
            $extra_expenses = Extraexpence::all();

            $customerExpenses = json_decode($selectedcustomer->extra_expenses);

            if ($customerExpenses !== null) {
                foreach ($customerExpenses as $expense) {
                    $expense->date = now()->format('Y-m-d');  // Add date to each expense
                }
                $balanceAccounting = $customerExpenses;
            } else {
                return view('pages.htmx.htmxExtraExpenseCustomer', compact('selectedcustomer'));
            }


            return view('pages.htmx.htmxExtraExpenseCustomer',
                compact('selectedcustomer', 'extra_expenses', 'balanceAccounting'));
        }

        return view('pages.htmx.htmxExtraExpenseCustomer', compact('selectedcustomer'));
    }
}
