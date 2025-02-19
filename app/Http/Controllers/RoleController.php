<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles       = Role::with('users', 'permissions')->get();
        $permissions = Permission::all();

        return view('pages.roles', compact('roles', 'permissions'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Role::create(['name' => $request->name]);

        return back();
    }

    public function updateRole(Request $request) {

        $role=Role::find($request->id);
        $role->name=$request->name;
        $role->save();
        return back();
    }

    public function deleteRole(Request $request) {
        $role=Role::find($request->id);
        $role->delete();
        return back();
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        Permission::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return back();
    }

    public function updatePermission(Request $request) {

        $permission= Permission::find($request->id);
        $permission->name        = $request->name;
        $permission->description = $request->description;
        $permission->save();

        return back();

    }

    public function deletePermission(Request $request) {

        $permission=Permission::findById($request->id);
        $permission->delete();

        return back();

    }


    public function rolePermissionAssign(Request $request)
    {
        $role        = Role::find($request->role_id);
        $permissions = Permission::all();

        foreach ($permissions as $permission1) {
            $role->revokePermissionTo($permission1->name);
        }

        if ($request->permission) {
            foreach ($request->permission as $permission) {
                $role->givePermissionTo($permission);
            }
        }

        return back();
    }

    public function rolePermissionRemove(Request $request) {}
}
