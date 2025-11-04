<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-commerce Store')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ... existing styles ... */
    
    .category-card {
        transition: all 0.3s ease;
    }
    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 20px rgba(0,0,0,.15) !important;
    }
    .category-card img {
        transition: transform 0.3s ease;
    }
    .category-card:hover img {
        transform: scale(1.1);
    }
        .top-bar {
            background: #333;
            color: white;
            padding: 10px 0;
            overflow: hidden;
        }
        .top-bar-slider {
            animation: slide 15s linear infinite;
        }
        @keyframes slide {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .category-card {
            transition: transform 0.3s;
            cursor: pointer;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .product-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,.2);
        }
        .product-image {
            height: 250px;
            object-fit: cover;
        }
        footer {
            background: #2c3e50;
            color: white;
            margin-top: 50px;
        }
        footer a {
            text-decoration: none;
            color: white;
        }
        footer a:hover {
            color: #3498db;
        }
        .badge-cart {
            position: absolute;
            top: -8px;
            right: -8px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Top Bar with Sliding Messages -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-slider">
                @if(isset($topBars))
                    @foreach($topBars as $topBar)
                        <span class="me-5">{{ $topBar->message }}</span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

   <!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-shopping-bag"></i> MyShop
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}" href="{{ route('shop') }}">
                        <i class="fas fa-store"></i> Shop
                    </a>
                </li>
            </ul>
            
            <!-- SEARCH BAR (NEW) -->
            <form action="{{ route('shop') }}" method="GET" class="d-flex me-3" style="min-width: 300px;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search products..." 
                           value="{{ request('search') }}"
                           aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <ul class="navbar-nav">
                <!-- Cart -->
                <li class="nav-item position-relative me-3">
                    <a class="nav-link" href="{{ route('cart.index') }}" title="Shopping Cart">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        @auth
                            @php
                                $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
                            @endphp
                            @if($cartCount > 0)
                                <span class="badge bg-danger badge-cart">{{ $cartCount }}</span>
                            @endif
                        @endauth
                    </a>
                </li>
                
                <!-- User Authentication -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- Admin Dashboard Link - Only show for admins -->
                            @if(Auth::user()->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user-circle"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="fas fa-box"></i> My Orders
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="container mt-3">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="container mt-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5><i class="fas fa-shopping-bag"></i> MyShop</h5>
                    <p class="mt-3">Your trusted online shopping destination for quality products at great prices.</p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3" title="Facebook">
                            <i class="fab fa-facebook fa-lg"></i>
                        </a>
                        <a href="#" class="text-white me-3" title="Twitter">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                        <a href="#" class="text-white me-3" title="Instagram">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                        <a href="#" class="text-white" title="LinkedIn">
                            <i class="fab fa-linkedin fa-lg"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2">
                            <a href="{{ route('home') }}" class="text-white">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('shop') }}" class="text-white">
                                <i class="fas fa-store"></i> Shop
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white">
                                <i class="fas fa-info-circle"></i> About Us
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5>Customer Service</h5>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2">
                            <a href="#" class="text-white">
                                <i class="fas fa-question-circle"></i> FAQ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white">
                                <i class="fas fa-shipping-fast"></i> Shipping Info
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white">
                                <i class="fas fa-undo"></i> Returns
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white">
                                <i class="fas fa-shield-alt"></i> Privacy Policy
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white">
                                <i class="fas fa-file-contract"></i> Terms & Conditions
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt"></i> 123 Shopping Street<br>
                            <span class="ms-4">New York, NY 10001</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope"></i> 
                            <a href="mailto:info@myshop.com" class="text-white">info@myshop.com</a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone"></i> 
                            <a href="tel:+12345678900" class="text-white">+1 (234) 567-8900</a>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock"></i> Mon - Fri: 9:00 AM - 6:00 PM
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="bg-white my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2025 MyShop. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                        <i class="fas fa-credit-card"></i> We accept: 
                        <i class="fab fa-cc-visa ms-2"></i>
                        <i class="fab fa-cc-mastercard ms-1"></i>
                        <i class="fab fa-cc-amex ms-1"></i>
                        <i class="fab fa-cc-paypal ms-1"></i>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    @push('scripts')
<script>
// Quick search with autocomplete
let searchTimeout;
const searchInput = document.querySelector('input[name="search"]');
const searchForm = searchInput ? searchInput.closest('form') : null;

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        
        if (this.value.length >= 3) {
            searchTimeout = setTimeout(() => {
                // You can add AJAX call here for live search suggestions
                console.log('Searching for:', this.value);
            }, 500);
        }
    });
}
</script>
@endpush

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto-hide alerts after 5 seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>