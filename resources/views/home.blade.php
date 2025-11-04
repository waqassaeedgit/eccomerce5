@extends('layouts.app')

@section('title', 'Home - E-commerce Store')

@section('content')
    <!-- Hero Slider -->
    <div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($sliders as $index => $slider)
                <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="{{ $index }}" 
                        class="{{ $index === 0 ? 'active' : '' }}"></button>
            @endforeach
        </div>
        
        <div class="carousel-inner">
            @foreach($sliders as $index => $slider)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset($slider->image) }}" class="d-block w-100" alt="{{ $slider->title }}" style="height: 500px; object-fit: cover;">
                    <div class="carousel-caption">
                        <h1>{{ $slider->title }}</h1>
                        <p>{{ $slider->description }}</p>
                        @if($slider->link)
                            <a href="{{ $slider->link }}" class="btn btn-primary btn-lg">Shop Now</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

<!-- Categories Section - AUTO SLIDING CAROUSEL -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Shop by Category</h2>
        
        @php
            $categoryChunks = $categories->chunk(5);
        @endphp
        
        <div id="categoriesCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                @foreach($categoryChunks as $chunkIndex => $categoryChunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row g-4 justify-content-center">
                            @foreach($categoryChunk as $category)
                                <div class="col-6 col-md-4 col-lg-2">
                                    <a href="{{ route('shop', ['category' => $category->id]) }}" class="text-decoration-none">
                                        <div class="card category-card text-center h-100 border-0 shadow-sm">
                                            @if($category->image)
                                                <div class="category-img-wrapper">
                                                    <img src="{{ asset($category->image) }}" 
                                                         alt="{{ $category->name }}" 
                                                         class="category-img">
                                                </div>
                                            @else
                                                <div class="category-icon-wrapper">
                                                    <i class="fas fa-tag fa-3x text-primary"></i>
                                                </div>
                                            @endif
                                            <div class="card-body p-2">
                                                <h6 class="card-title mb-1">{{ $category->name }}</h6>
                                                <small class="text-muted">{{ $category->products_count }} Products</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Previous/Next Buttons -->
            @if($categoryChunks->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#categoriesCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#categoriesCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                
                <!-- Indicators -->
                <div class="carousel-indicators category-indicators">
                    @foreach($categoryChunks as $chunkIndex => $chunk)
                        <button type="button" data-bs-target="#categoriesCarousel" data-bs-slide-to="{{ $chunkIndex }}" 
                                class="{{ $chunkIndex === 0 ? 'active' : '' }}" 
                                aria-current="{{ $chunkIndex === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $chunkIndex + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

@push('styles')
<style>
/* Category Carousel Styles */
#categoriesCarousel {
    position: relative;
    padding: 20px 0 60px;
}

.category-card {
    transition: all 0.3s ease;
    cursor: pointer;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 20px rgba(0,0,0,.2) !important;
}

.category-img-wrapper {
    height: 150px;
    overflow: hidden;
    background: #f8f9fa;
    position: relative;
}

.category-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-img {
    transform: scale(1.15);
}

.category-icon-wrapper {
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

/* Carousel Controls */
#categoriesCarousel .carousel-control-prev,
#categoriesCarousel .carousel-control-next {
    width: 5%;
    opacity: 0.8;
}

#categoriesCarousel .carousel-control-prev:hover,
#categoriesCarousel .carousel-control-next:hover {
    opacity: 1;
}

/* Carousel Indicators */
.category-indicators {
    bottom: -40px !important;
}

.category-indicators button {
    background-color: #333 !important;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.category-indicators button.active {
    background-color: #007bff !important;
}

/* Responsive */
@media (max-width: 768px) {
    .category-img-wrapper,
    .category-icon-wrapper {
        height: 120px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize category carousel
    var categoriesCarouselEl = document.getElementById('categoriesCarousel');
    if (categoriesCarouselEl) {
        var categoriesCarousel = new bootstrap.Carousel(categoriesCarouselEl, {
            interval: 3000,  // Auto-slide every 3 seconds
            wrap: true,      // Loop continuously
            pause: 'hover',  // Pause on mouse hover
            ride: 'carousel' // Start automatically
        });
        
        console.log('✓ Categories carousel initialized and auto-playing');
    } else {
        console.error('✗ Categories carousel element not found');
    }
});
</script>
@endpush
                
                <!-- Previous/Next Buttons -->
                @if($categoryChunks->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#categoriesCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#categoriesCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    
                    <!-- Indicators -->
                    <div class="carousel-indicators category-indicators">
                        @foreach($categoryChunks as $chunkIndex => $chunk)
                            <button type="button" data-bs-target="#categoriesCarousel" data-bs-slide-to="{{ $chunkIndex }}" 
                                    class="{{ $chunkIndex === 0 ? 'active' : '' }}" 
                                    aria-current="{{ $chunkIndex === 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $chunkIndex + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Featured Products</h2>
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                    <div class="col-md-6 col-lg-3">
                        <div class="card product-card">
                            <img src="{{ asset($product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="text-muted small">{{ $product->category->name }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($product->sale_price)
                                            <span class="text-decoration-line-through text-muted">${{ $product->price }}</span>
                                            <span class="text-danger fw-bold">${{ $product->sale_price }}</span>
                                        @else
                                            <span class="fw-bold">${{ $product->price }}</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-primary w-100 mt-3">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
/* Category Carousel Styles */
#categoriesCarousel {
    position: relative;
    padding: 20px 0 60px;
}

.category-card {
    transition: all 0.3s ease;
    cursor: pointer;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 20px rgba(0,0,0,.2) !important;
}

.category-img-wrapper {
    height: 150px;
    overflow: hidden;
    background: #f8f9fa;
    position: relative;
}

.category-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-img {
    transform: scale(1.15);
}

.category-icon-wrapper {
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

/* Carousel Controls */
#categoriesCarousel .carousel-control-prev,
#categoriesCarousel .carousel-control-next {
    width: 5%;
    opacity: 0.8;
}

#categoriesCarousel .carousel-control-prev:hover,
#categoriesCarousel .carousel-control-next:hover {
    opacity: 1;
}

/* Carousel Indicators */
.category-indicators {
    bottom: -40px !important;
}

.category-indicators button {
    background-color: #333 !important;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.category-indicators button.active {
    background-color: #007bff !important;
}

/* Responsive */
@media (max-width: 768px) {
    .category-img-wrapper,
    .category-icon-wrapper {
        height: 120px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize category carousel
    var categoriesCarouselEl = document.getElementById('categoriesCarousel');
    if (categoriesCarouselEl) {
        var categoriesCarousel = new bootstrap.Carousel(categoriesCarouselEl, {
            interval: 3000,  // Auto-slide every 3 seconds
            wrap: true,      // Loop continuously
            pause: 'hover',  // Pause on mouse hover
            ride: 'carousel' // Start automatically
        });
        
        console.log('✓ Categories carousel initialized and auto-playing');
    } else {
        console.error('✗ Categories carousel element not found');
    }
});
</script>
@endpush