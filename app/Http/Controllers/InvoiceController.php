<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CustomerBalance;
use App\Services\CreditService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generateInvoice(Request $request) {

        $customer=auth()->user();
        $car = Car::with('customer')
            ->where('id', $request->car_id)
            ->where('customer_id', $customer->id)
            ->first();
        if ($car===null){
            return back();
        }

        $invNumber=random_int(1000000,9999999);

        $costsdraft = json_decode($car->balance_accounting, true);

        $costs = [];

        foreach ($costsdraft as $item) {
            $name = $item['name'];
            $value = (float)$item['value']; // Convert value to numeric

            if (!isset($costs[$name])) {
                // Initialize if name doesn't exist
                $costs[$name] = ['name' => $name, 'value' => 0];
            }

            // Sum up the values
            $costs[$name]['value'] += $value;
        }

// Re-index the result to maintain a clean array structure
        $result = array_values($costs);


        $payment=CustomerBalance::where('car_id',$car->id)
        ->where('type','car_payment')->sum('amount');

        $totalinterest=round( ((new CreditService())->totalAccruedInterestTillToday($car->id)));

//        dd($totalinterest);

        return view('frontend.pages.invoice', compact('car', 'invNumber', 'customer','costs','payment','totalinterest'));
    }
}
