@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stats-card" style="border-left-color: #3498db;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Orders</h6>
                            <h2 class="mb-0">{{ $totalOrders }}</h2>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-shopping-cart fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stats-card" style="border-left-color: #2ecc71;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Products</h6>
                            <h2 class="mb-0">{{ $totalProducts }}</h2>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-box fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stats-card" style="border-left-color: #f39c12;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Customers</h6>
                            <h2 class="mb-0">{{ $totalCustomers }}</h2>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stats-card" style="border-left-color: #e74c3c;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Revenue</h6>
                            <h2 class="mb-0">${{ number_format($totalRevenue, 2) }}</h2>
                        </div>
                        <div class="text-danger">
                            <i class="fas fa-dollar-sign fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td><span class="badge {{ $order->getStatusBadgeClass() }}">{{ ucfirst($order->status) }}</span></td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No orders yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders by Status & Low Stock -->
        <div class="col-md-4">
            <!-- Orders by Status -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Orders by Status</h5>
                </div>
                <div class="card-body">
                    @foreach($ordersByStatus as $status)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ ucfirst($status->status) }}</span>
                                <strong>{{ $status->count }}</strong>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" style="width: {{ ($status->count / $totalOrders) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle text-warning"></i> Low Stock Products</h5>
                </div>
                <div class="card-body">
                    @forelse($lowStockProducts as $product)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <strong>{{ $product->name }}</strong><br>
                                <small class="text-muted">Stock: {{ $product->stock }}</small>
                            </div>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    @empty
                        <p class="text-muted mb-0">All products have sufficient stock</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection