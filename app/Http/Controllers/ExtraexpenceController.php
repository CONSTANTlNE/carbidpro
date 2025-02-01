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
//                $balanceAccounting = $customerExpenses;

            } else {
                return view('pages.htmx.htmxExtraExpenseCustomer', compact('selectedcustomer'));
            }



            return view('pages.htmx.htmxExtraExpenseCustomer',
                compact('selectedcustomer', 'extra_expenses', 'customerExpenses'));
        }

    }


    public function htmxinsertExtraExpense(Request $request)
    {

        $expenseName = $request->expense_name;
        $selectedcustomer = Customer::where('id', $request->customer_id)->first();
        $customerExpenses = json_decode($selectedcustomer->extra_expenses);


//        dd($expenseName);
        if ($customerExpenses !== null && $request->expense_name) {

            foreach ($customerExpenses as $expense) {
                $expense->date = now()->format('Y-m-d');
            }

            $filteredExpenses = array_filter($customerExpenses, function ($expense) use ($expenseName) {
                return in_array($expense->name, $expenseName);
            });

            $balanceAccounting = array_values($filteredExpenses);


        } else {
            return view('pages.htmx.htmxSelectExtraExpense', compact('selectedcustomer'));
        }

        return view('pages.htmx.htmxSelectExtraExpense', compact('balanceAccounting'));
    }
}
