<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // Display the roles page
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    // Store a newly created role
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->back()->with('success', __('dashboard.success_message'));
    }

    // Update the specified role
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array'
        ]);

        // Update name (unless it's Super Admin, we keep the original name)
        if ($role->name !== 'Super Admin') {
            $role->name = $request->name;
            $role->save();
        }

        // Update permissions
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            // If none are selected, revoke all permissions (except from Super Admin)
            if ($role->name !== 'Super Admin') {
                $role->syncPermissions([]);
            }
        }

        return redirect()->back()->with('success', __('dashboard.success_message'));
    }

    // Remove the specified role (with protection)
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Prevent deletion of the administrative role
        if ($role->name == 'admin' || $role->name == 'Super Admin') {
            return redirect()->back()->with('error', 'Cannot delete critical administrative roles');
        }

        $role->delete();
        return redirect()->back()->with('success', __('dashboard.success_message'));
    }
}
