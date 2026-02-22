<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function myOrders()
    {
        // Page protection: If the user is not logged in, redirect to the login page
        if (!session()->has('token')) {
            return redirect()->route('login');
        }

        // Fetch orders from the back-end using the session token
        $response = Http::withToken(session('token'))
                        ->get(env('BACKEND_API_URL') . '/my-orders');

        $orders = $response->successful() ? $response->json('orders') : [];

        return view('orders.index', compact('orders'));
    }
}
