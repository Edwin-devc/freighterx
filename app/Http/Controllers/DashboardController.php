<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalCustomers = Customer::count();
        $inTransitOrders = Order::where('status', 'in_transit')->count();

        // Get recent orders with relationships
        $recentOrders = Order::with(['customer', 'category'])
            ->latest()
            ->take(4)
            ->get();

        // Get recent customers with order count
        $recentCustomers = Customer::withCount('orders')
            ->latest()
            ->take(4)
            ->get();

        // Calculate percentage changes (mock for now, would need historical data)
        $ordersChange = 12;
        $customersChange = 8;

        return view('dashboard', compact(
            'totalOrders',
            'totalCustomers',
            'inTransitOrders',
            'recentOrders',
            'recentCustomers',
            'ordersChange',
            'customersChange'
        ));
    }
}
