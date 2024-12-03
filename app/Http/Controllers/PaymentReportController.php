<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Customer;
use App\Models\PaymentReport;
use Illuminate\Http\Request;

class PaymentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment_reports = PaymentReport::with(['car', 'customer'])->orderBy('created_at', 'desc')->get(); // Adjust the query to suit your admin access logic
        $cars = Car::all();
        $customers = Customer::all();
        return view('pages.payment-report.index', compact('payment_reports', 'cars', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        PaymentReport::create([
            'car_id' => $request->car_id,
            'customer_id' => $request->customer_id,
            'left_amount' => $request->left_amount,
            'is_approved' => isset($request->is_approved) ? 1 : 0,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Record added successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment_report = PaymentReport::findOrFail($id);

        // Update the user's information
        $payment_report->car_id = $request->input('car_id');
        $payment_report->customer_id = $request->input('customer_id');
        $payment_report->left_amount = $request->input('left_amount');
        $payment_report->is_approved = isset($request->is_approved) ? 1 : 0;

        // Save the changes to the database
        $payment_report->save();

        // Return a success response (this is for AJAX; adjust as necessary)
        return response()->json([
            'message' => 'Record updated successfully!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment_report = PaymentReport::findOrFail($id);
        $payment_report->delete();

        // Return success response
        return response()->json([
            'message' => 'Record deleted successfully!',
        ]);
    }
}
