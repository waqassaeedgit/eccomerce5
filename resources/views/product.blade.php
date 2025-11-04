@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop', ['category' => $product->category_id]) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <img src="{{ asset($product->image) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->name }}</h1>
            
            <div class="mb-3">
                <span class="badge bg-info">{{ $product->category->name }}</span>
                @if($product->is_featured)
                    <span class="badge bg-warning text-dark">Featured</span>
                @endif
            </div>

            <!-- Price -->
            <div class="mb-4">
                @if($product->sale_price)
                    <h2 class="text-danger mb-0">
                        ${{ number_format($product->sale_price, 2) }}
                        <small class="text-muted text-decoration-line-through fs-5 ms-2">
                            ${{ number_format($product->price, 2) }}
                        </small>
                        <span class="badge bg-danger ms-2">
                            {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                        </span>
                    </h2>
                @else
                    <h2 class="text-primary">${{ number_format($product->price, 2) }}</h2>
                @endif
            </div>

            <!-- Description -->
            <div class="mb-4">
                <h5>Description</h5>
                <p class="text-muted">{{ $product->description }}</p>
            </div>

            <!-- Stock Status -->
            <div class="mb-4">
                @if($product->stock > 0)
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-check-circle"></i> In Stock ({{ $product->stock }} available)
                    </span>
                @else
                    <span class="badge bg-danger fs-6">
                        <i class="fas fa-times-circle"></i> Out of Stock
                    </span>
                @endif
            </div>

            <!-- Add to Cart Form -->
            @if($product->stock > 0)
                @auth
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-auto">
                                <label for="quantity" class="form-label fw-bold">Quantity:</label>
                                <input 
                                    type="number" 
                                    name="quantity" 
                                    id="quantity" 
                                    class="form-control form-control-lg" 
                                    value="1" 
                                    min="1" 
                                    max="{{ $product->stock }}" 
                                    style="width: 120px;"
                                    required
                                >
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Please <a href="{{ route('login') }}" class="alert-link">login</a> to add items to your cart.
                    </div>
                @endauth
            @else
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> This product is currently out of stock.
                </div>
            @endif

            <!-- Additional Info -->
            <div class="mt-4 p-3 bg-light rounded">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-truck text-primary"></i> Free shipping on orders over $50</li>
                    <li class="mb-2"><i class="fas fa-undo text-primary"></i> 30-day return policy</li>
                    <li><i class="fas fa-shield-alt text-primary"></i> 1-year warranty</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h3 class="mb-4">Related Products</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-md-3">
                        <div class="card h-100 product-card">
                            <img src="{{ asset($relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ Str::limit($relatedProduct->name, 30) }}</h5>
                                <div class="mb-2">
                                    @if($relatedProduct->sale_price)
                                        <span class="text-danger fw-bold">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                        <small class="text-muted text-decoration-line-through ms-1">${{ number_format($relatedProduct->price, 2) }}</small>
                                    @else
                                        <span class="fw-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('product.show', $relatedProduct->slug) }}" class="btn btn-sm btn-outline-primary w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,.2);
    }
</style>
@endpush
@endsection