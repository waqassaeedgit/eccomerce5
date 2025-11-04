@extends('layouts.app')

@section('title', 'Shop - All Products')

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Categories</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('shop') }}" class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                            All Products
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('shop', ['category' => $category->id]) }}" 
                               class="list-group-item list-group-item-action {{ request('category') == $category->id ? 'active' : '' }}">
                                {{ $category->name }} ({{ $category->products_count }})
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Search -->
                <div class="card">
                    <div class="card-body">
                        <h5>Search Products</h5>
                        <form action="{{ route('shop') }}" method="GET">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Search</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>All Products</h2>
        @if(request('search'))
            <p class="text-muted">
                Showing results for: <strong>"{{ request('search') }}"</strong>
                <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="fas fa-times"></i> Clear Search
                </a>
            </p>
        @endif
    </div>
    <div>
        <select class="form-select" onchange="location = this.value;">
            <option value="{{ route('shop') }}">Sort by</option>
            <option value="{{ route('shop', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
            <option value="{{ route('shop', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
            <option value="{{ route('shop', array_merge(request()->except('sort'), ['sort' => 'name'])) }}" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
        </select>
    </div>
</div>

                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="card product-card h-100">
                                <img src="{{ asset($product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="text-muted small">{{ $product->category->name }}</p>
                                    <p class="card-text flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                @if($product->sale_price)
                                                    <span class="text-decoration-line-through text-muted small">${{ $product->price }}</span>
                                                    <span class="text-danger fw-bold ms-2">${{ $product->sale_price }}</span>
                                                @else
                                                    <span class="fw-bold">${{ $product->price }}</span>
                                                @endif
                                            </div>
                                            <span class="badge bg-success">In Stock: {{ $product->stock }}</span>
                                        </div>
                                        
                                        <!-- View Details Button -->
                                        <a href="{{ route('product.show', $product->slug) }}" class="btn btn-primary w-100 mb-2">
                                            View Details
                                        </a>

                                        <!-- Add to Cart Button -->
                                        @auth
                                            @if($product->stock > 0)
                                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-success w-100">
                                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-secondary w-100" disabled>
                                                    Out of Stock
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                                                Login to Buy
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">No products found.</div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection