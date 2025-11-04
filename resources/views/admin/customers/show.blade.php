@extends('admin.layouts.app')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-5x text-muted"></i>
                    </div>
                    
                    <p><strong>Name:</strong> {{ $customer->name }}</p>
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                    <p><strong>Joined:</strong> {{ $customer->created_at->format('M d, Y') }}</p>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="text-primary">{{ $totalOrders }}</h3>
                            <p class="text-muted mb-0">Total Orders</p>
                        </div>
                        <div class="col-6">
                            <h3 class="text-success">${{ number_format($totalSpent, 2) }}</h3>
                            <p class="text-muted mb-0">Total Spent</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order History</h5>
                </div>
                <div class="card-body">
                    @forelse($customer->orders as $order)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('admin.orders.show', $order->id) }}">
                                        <strong>{{ $order->order_number }}</strong>
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <div class="mb-1">
                                        <span class="badge {{ $order->getStatusBadgeClass() }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <strong class="text-success">${{ number_format($order->total, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No orders yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Customers
        </a>
    </div>
@endsection
