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
        $vehicleCost = null; // Variable to store Vehicle cost separately

        foreach ($costsdraft as $item) {
            $name = $item['name'];
            $value = (float)$item['value']; // Convert value to numeric

            if ($name === 'Vehicle cost') {
                // Handle Vehicle cost separately
                if (!isset($vehicleCost)) {
                    $vehicleCost = ['name' => $name, 'value' => 0];
                }
                $vehicleCost['value'] += $value;
            } else {
                // Handle other costs
                if (!isset($costs[$name])) {
                    $costs[$name] = ['name' => $name, 'value' => 0];
                }
                $costs[$name]['value'] += $value;
            }
        }

// Add Vehicle cost first to the final array
        if ($vehicleCost) {
            $costs = ['Vehicle cost' => $vehicleCost] + $costs;
        }




        $payment=CustomerBalance::where('car_id',$car->id)
        ->where('type','car_payment')->sum('amount');

        $totalinterest=round( ((new CreditService())->totalAccruedInterestTillToday($car->id)));


        return view('frontend.pages.invoice', compact('car', 'invNumber', 'customer','costs','payment','totalinterest'));
    }
}
