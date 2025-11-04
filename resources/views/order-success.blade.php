@extends('layouts.app')

@section('title', 'Order Successful')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Message -->
            <div class="text-center mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                <h1 class="mt-3">Order Placed Successfully!</h1>
                <p class="lead">Thank you for your purchase</p>
            </div>

            <!-- Order Details Card -->
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Order Number:</strong></p>
                            <p class="text-primary fs-5">{{ $order->order_number }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Order Date:</strong></p>
                            <p>{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Customer Info -->
                    <h6 class="mb-3"><i class="fas fa-user"></i> Customer Information</h6>
                    <p class="mb-1"><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p class="mb-3"><strong>Phone:</strong> {{ $order->customer_phone }}</p>

                    <hr>

                    <!-- Shipping Address -->
                    <h6 class="mb-3"><i class="fas fa-map-marker-alt"></i> Shipping Address</h6>
                    <p class="mb-0">
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}
                    </p>

                    <hr>

                    <!-- Order Items -->
                    <h6 class="mb-3"><i class="fas fa-box"></i> Order Items</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->product_price, 2) }}</td>
                                        <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                    <td class="text-end">${{ number_format($order->shipping_cost, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                    <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <hr>

                    <!-- Payment Method -->
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>Payment Method:</strong>
                            <span class="ms-2">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                        <div>
                            <span class="badge {{ $order->getStatusBadgeClass() }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary me-2">
                    <i class="fas fa-eye"></i> View Order Details
                </a>
                <a href="{{ route('shop') }}" class="btn btn-outline-primary">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
            </div>

            <!-- Confirmation Email Notice -->
            <div class="alert alert-info mt-4">
                <i class="fas fa-envelope"></i>
                A confirmation email has been sent to <strong>{{ $order->customer_email }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection
