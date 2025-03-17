<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Customer;
use App\Models\CustomerBalance;
use App\Models\Extraexpence;
use Illuminate\Http\Request;

class ExtraexpenceController extends Controller
{
    public function htmxGetExtraExpense(Request $request)
    {
        $selectedcustomer = Customer::where('id', $request->customer_id)->first();
        $deposit          = CustomerBalance::where('customer_id', $request->customer_id)->where('is_approved',
            1)->sum('amount');

        if ($selectedcustomer) {
            $extra_expenses = Extraexpence::all();

            $customerExpenses = json_decode($selectedcustomer->extra_expenses);

            if ($customerExpenses !== null) {
                foreach ($customerExpenses as $expense) {
                    $expense->date      = now()->format('Y-m-d');
                    $expense->id        = random_int(10000, 99999);
                    $expense->forcredit = 1;
                }
//                $balanceAccounting = $customerExpenses;

            } else {
                return view('pages.htmx.htmxExtraExpenseCustomer', compact('selectedcustomer', 'deposit'));
            }


            return view('pages.htmx.htmxExtraExpenseCustomer',
                compact('selectedcustomer', 'extra_expenses', 'customerExpenses', 'deposit'));
        }
    }


    public function htmxinsertExtraExpense(Request $request)
    {
        $expenseName      = $request->expense_name;
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


    public function index() {

        $extras=Extraexpence::all();

        return view('pages.Data.customerextraexpenses',compact('extras'));
    }

    public function create(Request $request) {

        $request->validate([
           'name'=>'required|string|max:255'
        ]);

       Extraexpence::create([
          'name'=>$request->name
       ]);

       return back();

    }

    public function update(Request $request) {

        $request->validate([
            'name'=>'required|string|max:255'
        ]);


        Extraexpence::where('id', $request->id)->update([
            'name' => $request->name
        ]);


        return back();
    }

    public function delete(Request $request) {

        $extra=Extraexpence::find($request->id);
        $extra->delete();
        return back();
    }


}
