<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('orders')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        $totalCustomers = Customer::where('user_id', auth()->id())->count();
        $activeCount = Customer::where('user_id', auth()->id())->where('status', 'active')->count();
        $inactiveCount = Customer::where('user_id', auth()->id())->where('status', 'inactive')->count();

        return view('customers', compact('customers', 'totalCustomers', 'activeCount', 'inactiveCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $customer = Customer::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => 'active',
        ]);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'customer' => $customer
            ]);
        }

        return redirect()->route('customers')->with('success', 'Customer created successfully');
    }
}
