@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-receipt"></i> Order Details</h2>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-8">
            <!-- Order Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Order #{{ $order->order_number }}</h5>
                            <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p class="mb-1"><strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                            <p class="mb-1">
                                <strong>Status:</strong>
                                <span class="badge {{ $order->getStatusBadgeClass() }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            @if($order->status === 'pending' || $order->status === 'processing')
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                                        <i class="fas fa-times"></i> Cancel Order
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user"></i> Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Name:</strong> {{ $order->customer_name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Shipping Address:</strong></p>
                            <p class="mb-0">
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                                {{ $order->shipping_country }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-box"></i> Order Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product)
                                                    <img src="{{ asset($item->product->image) }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         style="width: 50px; height: 50px; object-fit: cover;" 
                                                         class="me-3 rounded">
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product_name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->product_price, 2) }}</td>
                                        <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            @if($order->notes)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-sticky-note"></i> Order Notes</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Summary Sidebar -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-calculator"></i> Order Summary</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong class="fs-5">Total:</strong>
                        <strong class="fs-5 text-success">${{ number_format($order->total, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Order Status Timeline -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-clock"></i> Order Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success"></i>
                            <strong>Order Placed</strong>
                            <br>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                        </li>
                        @if($order->status === 'processing' || $order->status === 'shipped' || $order->status === 'delivered')
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success"></i>
                                <strong>Processing</strong>
                            </li>
                        @endif
                        @if($order->shipped_at)
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success"></i>
                                <strong>Shipped</strong>
                                <br>
                                <small class="text-muted">{{ $order->shipped_at->format('M d, Y h:i A') }}</small>
                            </li>
                        @endif
                        @if($order->delivered_at)
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success"></i>
                                <strong>Delivered</strong>
                                <br>
                                <small class="text-muted">{{ $order->delivered_at->format('M d, Y h:i A') }}</small>
                            </li>
                        @endif
                        @if($order->status === 'cancelled')
                            <li class="mb-2">
                                <i class="fas fa-times-circle text-danger"></i>
                                <strong>Order Cancelled</strong>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection