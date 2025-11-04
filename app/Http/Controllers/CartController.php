<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
                            ->with('product.category')
                            ->get();
        
        $subtotal = $cartItems->sum(function($item) {
            $price = $item->product->sale_price ?? $item->product->price;
            return $price * $item->quantity;
        });

        $shipping = 10.00;
        $total = $subtotal + $shipping;

        return view('cart', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        // Validate quantity
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        // Check if item already in cart
        $cartItem = CartItem::where('user_id', Auth::id())
                           ->where('product_id', $product->id)
                           ->first();

        if ($cartItem) {
            // Update existing cart item
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Not enough stock available!');
            }
            
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
            
            return redirect()->back()->with('success', 'Cart updated successfully!');
        } else {
            // Create new cart item
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
            
            return redirect()->back()->with('success', 'Product added to cart!');
        }
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // Check if cart item belongs to current user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate quantity
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function remove(CartItem $cartItem)
    {
        // Check if cart item belongs to current user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function clear()
    {
        CartItem::where('user_id', Auth::id())->delete();

        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }

    // Get cart count for navbar badge
    public function getCartCount()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->sum('quantity');
        }
        return 0;
    }
}