<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get(); // Adjust the query to suit your admin access logic
        $roles = Role::all();

        return view('pages.users.index', compact('users', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);


        // Create the new user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        $user->assignRole($request->role);

        return back()->with('success', 'User created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);

        $request->validate([
            'name'     => 'required|string|max:255',
//            'email'    => 'required|email|max:255|unique:users,email,'.$user->id,
            // Ensure the email is unique, except for this user
            'password' => 'nullable|confirmed|min:6',
            // Validate password and confirmation, but it's optional (nullable)
        ]);

        // Update the user's information
        $user->name  = $request->input('name');
        $user->email = $request->input('email');
        $user->assignRole($request->role);

        // If a password is provided, hash and update it
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Save the changes to the database
        $user->save();

        // Return a success response (this is for AJAX; adjust as necessary)
        return back()->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = User::findOrFail($request->user_id);
        $user->delete();


        return back()->with('success', 'User deleted successfully!');
    }
}
