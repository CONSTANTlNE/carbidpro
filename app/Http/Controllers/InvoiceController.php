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
//dd($costsdraft);
        $costs = [];
        $vehicleCost = null; // Variable to store Vehicle cost separately

        foreach ($costsdraft as $item) {
            $name = $item['name'];
            $value = (float)$item['value']; // Convert value to numeric

            if ($name === 'Vehicle cost') {
                // Sum Vehicle cost separately
                if (!isset($vehicleCost)) {
                    $vehicleCost = ['name' => $name, 'value' => 0];
                }
                $vehicleCost['value'] += $value;
            } else {
                // Store all items separately instead of overwriting
                $costs[] = ['name' => $name, 'value' => $value];
            }
        }

// Add Vehicle cost at the beginning if it exists
        if ($vehicleCost) {
            array_unshift($costs, $vehicleCost);
        }




//dd    ($costsdraft, $costs);


        $payment=CustomerBalance::where('car_id',$car->id)
        ->where('type','car_payment')->sum('amount');

        $totalinterest=round( ((new CreditService())->totalAccruedInterestTillToday($car)));


        return view('frontend.pages.invoice', compact('car', 'invNumber', 'customer','costs','payment','totalinterest'));
    }
}
