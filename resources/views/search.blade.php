@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Search Products</h2>
            
            <!-- Advanced Search Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('shop') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="search" class="form-label">Search Keywords</label>
                                <input type="text" name="search" id="search" class="form-control" 
                                       placeholder="Product name, description..." 
                                       value="{{ request('search') }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="sort" class="form-label">Sort By</label>
                                <select name="sort" id="sort" class="form-select">
                                    <option value="">Latest</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <a href="{{ route('shop') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection