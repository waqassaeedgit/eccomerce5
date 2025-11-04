<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Products</h4>
    <div class="d-flex gap-2">
        <!-- Search Form -->
        <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" 
                   placeholder="Search products..." 
                   value="{{ request('search') }}"
                   style="min-width: 250px;">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
        
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary ms-2">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>
</div>
@extends('admin.layouts.app')

@section('title', 'Products')
@section('page-title', 'Products Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">All Products</h4>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                         style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->is_featured)
                                        <span class="badge bg-warning text-dark">Featured</span>
                                    @endif
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td>
                                    @if($product->sale_price)
                                        <span class="text-danger">${{ number_format($product->sale_price, 2) }}</span>
                                        <br>
                                        <small class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</small>
                                    @else
                                        ${{ number_format($product->price, 2) }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $product->stock < 10 ? 'bg-danger' : 'bg-success' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="table-actions">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection