<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show checkout page
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
                            ->with('product')
                            ->get();

        if ($cartItems->count() === 0) {
            return redirect()->route('shop')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(function($item) {
            $price = $item->product->sale_price ?? $item->product->price;
            return $price * $item->quantity;
        });

        $shipping = 10.00;
        $tax = $subtotal * 0.10; // 10% tax
        $total = $subtotal + $shipping + $tax;

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    // Process order
    public function placeOrder(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'payment_method' => 'required|in:credit_card,debit_card,paypal,cash_on_delivery',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Get cart items
        $cartItems = CartItem::where('user_id', Auth::id())
                            ->with('product')
                            ->get();

        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            $price = $item->product->sale_price ?? $item->product->price;
            return $price * $item->quantity;
        });

        $shipping = 10.00;
        $tax = $subtotal * 0.10; // 10% tax
        $total = $subtotal + $shipping + $tax;

        // Start database transaction
        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_zip' => $validated['shipping_zip'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $price = $cartItem->product->sale_price ?? $cartItem->product->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_price' => $price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $price * $cartItem->quantity,
                ]);

                // Update product stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Clear cart
            CartItem::where('user_id', Auth::id())->delete();

            // Commit transaction
            DB::commit();

            return redirect()->route('order.success', $order->id)
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            return redirect()->back()
                           ->with('error', 'Failed to place order. Please try again.')
                           ->withInput();
        }
    }

    // Order success page
    public function success($orderId)
    {
        $order = Order::with('orderItems.product')
                     ->where('user_id', Auth::id())
                     ->findOrFail($orderId);

        return view('order-success', compact('order'));
    }
}