<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Display a listing of employees/users
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        // Fetch all roles to display them in the selection menu
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    // Store a newly created employee and assign their role
    public function store(Request $request)
    {
        // Data Validation
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::defaults()],
            'role'     => 'required|exists:roles,name',
        ]);

        // Create the user record
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Assign the selected role to the employee (Spatie logic)
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
                         ->with('success', __('dashboard.success_message'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        // Update basic information
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Change password only if a new value is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update role (Sync replaces old roles with the new one)
        $user->syncRoles($request->role);

        return redirect()->back()->with('success', __('dashboard.success_message'));
    }

    // Remove the specified employee
    public function destroy(User $user)
    {
        // Prevent the employee from deleting their own account
        if (Auth::id() == $user->id) {
            return redirect()->back()->with('error', 'You cannot delete yourself!');
        }

        $user->delete();
        return redirect()->back()->with('success', __('dashboard.success_message'));
    }
}
