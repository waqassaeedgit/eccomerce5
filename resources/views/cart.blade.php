@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">
        <i class="fas fa-shopping-cart"></i> Shopping Cart
    </h2>

    @if($cartItems->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="row align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <!-- Product Image -->
                                <div class="col-md-2 col-3">
                                    <img src="{{ asset($item->product->image) }}" class="img-fluid rounded" alt="{{ $item->product->name }}">
                                </div>

                                <!-- Product Details -->
                                <div class="col-md-4 col-9">
                                    <h5 class="mb-1">
                                        <a href="{{ route('product.show', $item->product->slug) }}" class="text-decoration-none text-dark">
                                            {{ $item->product->name }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-1">{{ $item->product->category->name }}</p>
                                    <p class="mb-0">
                                        @if($item->product->sale_price)
                                            <span class="text-danger fw-bold">${{ number_format($item->product->sale_price, 2) }}</span>
                                            <small class="text-muted text-decoration-line-through ms-1">${{ number_format($item->product->price, 2) }}</small>
                                        @else
                                            <span class="fw-bold">${{ number_format($item->product->price, 2) }}</span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Quantity -->
                                <div class="col-md-3 col-6 mt-2 mt-md-0">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        @method('PATCH')
                                        <label class="me-2 small">Qty:</label>
                                        <input 
                                            type="number" 
                                            name="quantity" 
                                            value="{{ $item->quantity }}" 
                                            min="1" 
                                            max="{{ $item->product->stock }}"
                                            class="form-control form-control-sm me-2" 
                                            style="width: 70px;"
                                            onchange="this.form.submit()"
                                        >
                                    </form>
                                    <small class="text-muted">Max: {{ $item->product->stock }}</small>
                                </div>

                                <!-- Subtotal & Remove -->
                                <div class="col-md-2 col-6 mt-2 mt-md-0 text-md-end">
                                    <p class="fw-bold mb-2">
                                        ${{ number_format(($item->product->sale_price ?? $item->product->price) * $item->quantity, 2) }}
                                    </p>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove this item from cart?')">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Clear Cart Button -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('shop') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Clear entire cart?')">
                            <i class="fas fa-trash"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} items):</span>
                            <span class="fw-bold">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span class="fw-bold">${{ number_format($shipping, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong class="fs-5">Total:</strong>
                            <strong class="fs-5 text-primary">${{ number_format($total, 2) }}</strong>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-lock"></i> Proceed to Checkout
                        </a>
                        <a href="{{ route('shop') }}" class="btn btn-outline-primary w-100">Continue Shopping</a>

                        <!-- Promo Code -->
                        <div class="mt-3">
                            <label for="promoCode" class="form-label small">Have a promo code?</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="promoCode" placeholder="Enter code">
                                <button class="btn btn-outline-secondary" type="button">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body text-center">
                        <p class="small text-muted mb-2">We Accept:</p>
                        <div>
                            <i class="fab fa-cc-visa fa-2x text-primary me-2"></i>
                            <i class="fab fa-cc-mastercard fa-2x text-danger me-2"></i>
                            <i class="fab fa-cc-amex fa-2x text-info me-2"></i>
                            <i class="fab fa-cc-paypal fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>

                <!-- Security Badge -->
                <div class="text-center mt-3">
                    <p class="small text-muted mb-1">
                        <i class="fas fa-shield-alt text-success"></i> Secure Checkout
                    </p>
                    <p class="small text-muted">
                        <i class="fas fa-lock text-success"></i> SSL Encrypted
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary btn-lg mt-3">
                <i class="fas fa-shopping-bag"></i> Start Shopping
            </a>
        </div>
    @endif
</div>

@push('styles')
<style>
    .card {
        transition: box-shadow 0.3s;
    }
    .card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,.1);
    }
</style>
@endpush
@endsection