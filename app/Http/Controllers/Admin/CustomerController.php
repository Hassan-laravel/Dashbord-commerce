<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CustomerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage-customers', except: ['index']),
            new Middleware('permission:view-customers|manage-customers', only: ['index']),
        ];
    }

    public function index()
    {
        // Fetch only users who have the 'customer' role
        // If roles aren't applied to all registrants yet, we fetch all except admins
        $customers = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'Super Admin')
              ->orWhere('name', 'Admin'); // Exclude administrators
        })->latest()->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$customer->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
        ]);

        $data = $request->except(['password', 'status']);

        // Update password only if it is provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update status
        $data['status'] = $request->has('status') ? 1 : 0;

        $customer->update($data);

        return redirect()->route('admin.customers.index')->with('success', __('dashboard.messages.updated_successfully'));
    }

    public function destroy(User $customer)
    {
        // Prevent deleting customer if they have existing orders (crucial for future integration)
        // if($customer->orders()->count() > 0) { ... }

        $customer->delete();
        return back()->with('success', __('dashboard.messages.deleted_successfully'));
    }
}
