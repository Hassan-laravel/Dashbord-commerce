<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // Display all roles
    public function index() {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    // Store a new role along with its permissions
    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:roles,name']);

        $role = Role::create(['name' => $request->name]);

        // Sync permissions if they are provided in the request
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->back()->with('success', __('Role created successfully'));
    }
}
