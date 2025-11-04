<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show all user orders
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->with('orderItems')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Show single order details
    public function show($id)
    {
        $order = Order::with('orderItems.product')
                     ->where('user_id', Auth::id())
                     ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    // Cancel order
    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status === 'pending' || $order->status === 'processing') {
            $order->update(['status' => 'cancelled']);
            return redirect()->back()->with('success', 'Order cancelled successfully!');
        }

        return redirect()->back()->with('error', 'This order cannot be cancelled!');
    }
}