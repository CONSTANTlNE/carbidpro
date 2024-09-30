<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::get(); // Adjust the query to suit your admin access logic
        return view('pages.users.index', compact('users'));
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:Admin,Editor,Dispatch',
            'password' => 'required|confirmed|min:6',
        ]);



        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        // Return success response
        return response()->json([
            'message' => 'User added successfully!',
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
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, // Ensure the email is unique, except for this user
            'role' => 'required|in:Admin,Editor,Dispatch', // Ensure role is one of the predefined roles
            'password' => 'nullable|confirmed|min:6', // Validate password and confirmation, but it's optional (nullable)
        ]);

        // Update the user's information
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');

        // If a password is provided, hash and update it
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Save the changes to the database
        $user->save();

        // Return a success response (this is for AJAX; adjust as necessary)
        return response()->json([
            'message' => 'User updated successfully!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // Return success response
        return response()->json([
            'message' => 'User deleted successfully!',
        ]);
    }
}
