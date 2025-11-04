@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-credit-card"></i> Checkout</h2>

    <div class="row">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <form action="{{ route('checkout.placeOrder') }}" method="POST">
                @csrf

                <!-- Customer Information -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" 
                                       value="{{ old('customer_name', Auth::user()->name) }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="customer_email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                       id="customer_email" name="customer_email" 
                                       value="{{ old('customer_email', Auth::user()->email) }}" required>
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                       id="customer_phone" name="customer_phone" 
                                       value="{{ old('customer_phone') }}" required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Street Address *</label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                      id="shipping_address" name="shipping_address" 
                                      rows="3" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="shipping_city" class="form-label">City *</label>
                                <input type="text" class="form-control @error('shipping_city') is-invalid @enderror" 
                                       id="shipping_city" name="shipping_city" 
                                       value="{{ old('shipping_city') }}" required>
                                @error('shipping_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="shipping_state" class="form-label">State *</label>
                                <input type="text" class="form-control @error('shipping_state') is-invalid @enderror" 
                                       id="shipping_state" name="shipping_state" 
                                       value="{{ old('shipping_state') }}" required>
                                @error('shipping_state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="shipping_zip" class="form-label">ZIP Code *</label>
                                <input type="text" class="form-control @error('shipping_zip') is-invalid @enderror" 
                                       id="shipping_zip" name="shipping_zip" 
                                       value="{{ old('shipping_zip') }}" required>
                                @error('shipping_zip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-credit-card"></i> Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="credit_card" value="credit_card" 
                                   {{ old('payment_method', 'credit_card') == 'credit_card' ? 'checked' : '' }}>
                            <label class="form-check-label" for="credit_card">
                                <i class="fab fa-cc-visa"></i> Credit Card
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="debit_card" value="debit_card"
                                   {{ old('payment_method') == 'debit_card' ? 'checked' : '' }}>
                            <label class="form-check-label" for="debit_card">
                                <i class="fab fa-cc-mastercard"></i> Debit Card
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="paypal" value="paypal"
                                   {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                            <label class="form-check-label" for="paypal">
                                <i class="fab fa-paypal"></i> PayPal
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="cash_on_delivery" value="cash_on_delivery"
                                   {{ old('payment_method') == 'cash_on_delivery' ? 'checked' : '' }}>
                            <label class="form-check-label" for="cash_on_delivery">
                                <i class="fas fa-money-bill-wave"></i> Cash on Delivery
                            </label>
                        </div>

                        @error('payment_method')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Order Notes (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" name="notes" rows="3" 
                                  placeholder="Any special instructions for your order?">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="fas fa-check-circle"></i> Place Order
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Order Summary</h5>
                </div>
                <div class="card-body">
                    <!-- Cart Items -->
                    @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <strong>{{ $item->product->name }}</strong>
                                <br>
                                <small class="text-muted">Qty: {{ $item->quantity }} × ${{ number_format($item->product->sale_price ?? $item->product->price, 2) }}</small>
                            </div>
                            <span class="fw-bold">
                                ${{ number_format(($item->product->sale_price ?? $item->product->price) * $item->quantity, 2) }}
                            </span>
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @endforeach

                    <hr class="my-3">

                    <!-- Totals -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>${{ number_format($shipping, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>${{ number_format($tax, 2) }}</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">
                        <strong class="fs-5">Total:</strong>
                        <strong class="fs-5 text-success">${{ number_format($total, 2) }}</strong>
                    </div>

                    <!-- Security Badge -->
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-shield-alt"></i> <small>Your payment information is secure</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection