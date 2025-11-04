@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <h2 class="mb-4"><i class="fas fa-box"></i> My Orders</h2>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Order Info -->
                                <div class="col-md-3">
                                    <h6 class="mb-1">Order #{{ $order->order_number }}</h6>
                                    <small class="text-muted">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </small>
                                </div>

                                <!-- Items Count -->
                                <div class="col-md-2">
                                    <span class="badge bg-info">
                                        {{ $order->orderItems->count() }} Item(s)
                                    </span>
                                </div>

                                <!-- Total -->
                                <div class="col-md-2">
                                    <strong class="text-success">
                                        ${{ number_format($order->total, 2) }}
                                    </strong>
                                </div>

                                <!-- Status -->
                                <div class="col-md-2">
                                    <span class="badge {{ $order->getStatusBadgeClass() }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>

                                <!-- Actions -->
                                <div class="col-md-3 text-end">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($order->status === 'pending' || $order->status === 'processing')
                                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this order?')">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Order Items Preview -->
                            <div class="mt-3">
                                <small class="text-muted">Items: 
                                    @foreach($order->orderItems->take(3) as $item)
                                        {{ $item->product_name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if($order->orderItems->count() > 3)
                                        and {{ $order->orderItems->count() - 3 }} more...
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <!-- No Orders -->
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-5x text-muted mb-3"></i>
            <h4>No orders yet</h4>
            <p class="text-muted">Start shopping to create your first order!</p>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-3">
                <i class="fas fa-shopping-bag"></i> Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection