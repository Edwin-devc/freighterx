<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'category'])
            ->latest()
            ->paginate(15);

        $totalOrders = Order::count();
        $pendingCount = Order::where('status', 'pending')->count();
        $inTransitCount = Order::where('status', 'in_transit')->count();
        $deliveredCount = Order::where('status', 'delivered')->count();

        return view('orders', compact('orders', 'totalOrders', 'pendingCount', 'inTransitCount', 'deliveredCount'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')
            ->orderBy('name')
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('customers', 'categories'));
    }

    public function store(Request $request, QRCodeService $qrCodeService)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'category_id' => 'required|exists:categories,id',
            'weight' => 'nullable|numeric|min:0',
            'origin_country' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Generate unique order number and tracking code
        $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 3, '0', STR_PAD_LEFT);
        $trackingCode = 'TRK-' . strtoupper(Str::random(8));

        // Create order first
        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_id' => $validated['customer_id'],
            'category_id' => $validated['category_id'],
            'order_number' => $orderNumber,
            'tracking_code' => $trackingCode,
            'weight' => $validated['weight'] ?? null,
            'origin_country' => $validated['origin_country'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        // Generate QR code with the order ID
        $qrCodePath = $qrCodeService->generateOrderQRCode($trackingCode, $orderNumber, $order->id);

        // Update order with QR code path
        $order->update(['qr_code' => $qrCodePath]);

        return redirect()->route('orders')->with('success', 'Order created successfully with QR code');
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'category', 'user'])
            ->where('id', $id)
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,loaded,in_transit,arrived,ready,delivered',
        ]);

        // Update status
        $order->status = $validated['status'];

        // Update timestamps based on status
        if ($validated['status'] === 'loaded' && !$order->loaded_at) {
            $order->loaded_at = now();
        }
        if ($validated['status'] === 'arrived' && !$order->arrived_at) {
            $order->arrived_at = now();
        }
        if ($validated['status'] === 'delivered' && !$order->delivered_at) {
            $order->delivered_at = now();
        }

        $order->save();

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order status updated successfully');
    }
}
