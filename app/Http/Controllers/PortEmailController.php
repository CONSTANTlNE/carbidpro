<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\PortEmail;
use Illuminate\Http\Request;

class PortEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $port_emails = PortEmail::with('port')->get(); // Adjust the query to suit your admin access logic
        $ports = Port::all();
        return view('pages.port-email.index', compact('port_emails', 'ports'));
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
        // Validate request data
        $request->validate([
            'email' => 'required|email|max:255',
            'port_email_id' => 'required',
        ]);


        // Create the new user
        $port_email = PortEmail::create([
            'email' => $request->email,
            'port_id' => $request->port_email_id,
        ]);

        $port_email->save();

        // Return success response
        return response()->json([
            'message' => 'Email added successfully!',
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
    public function update(Request $request)
    {
        // Validate request data
        $request->validate([
            'email' => 'required|email|max:255',
            'port_email_id' => 'required',
        ]);


        $portemail = PortEmail::find($request->id);
        
        $portemail->email = $request->email;
        $portemail->port_id = $request->port_email_id;
        $portemail->save();

        // Return success response
        return response()->json([
            'message' => 'Email Updated successfully!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
