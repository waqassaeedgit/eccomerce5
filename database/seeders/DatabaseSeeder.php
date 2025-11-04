<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\TopBar;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Top Bar Messages
        TopBar::create([
            'message' => 'Free Shipping on Orders Over $50! 🚚',
            'is_active' => true
        ]);

        TopBar::create([
            'message' => 'Summer Sale - Up to 50% Off! 🔥',
            'is_active' => true
        ]);

        // Create Sliders - Use local images
        Slider::create([
            'title' => 'Welcome to Our Store',
            'description' => 'Discover amazing products at great prices',
            'image' => 'images/slider.jpg', // Your local image
            'link' => '/shop',
            'order' => 1,
            'is_active' => true
        ]);

        Slider::create([
            'title' => 'Summer Sale',
            'description' => 'Get up to 50% off on selected items',
            'image' => 'images/slider.jpg', // Reuse or use different image
            'link' => '/shop',
            'order' => 2,
            'is_active' => true
        ]);

        Slider::create([
            'title' => 'New Arrivals',
            'description' => 'Check out our latest products',
            'image' => 'images/slider.jpg',
            'link' => '/shop',
            'order' => 3,
            'is_active' => true
        ]);

      // Create Categories with images
$categories = [
    [
        'name' => 'Electronics',
        'slug' => 'electronics',
        'image' => 'images/categories/electronics.jpg',
        'description' => 'Browse our Electronics collection'
    ],
    [
        'name' => 'Fashion',
        'slug' => 'fashion',
        'image' => 'images/categories/fashion.jpg',
        'description' => 'Browse our Fashion collection'
    ],
    [
        'name' => 'Home & Garden',
        'slug' => 'home-garden',
        'image' => 'images/categories/home-garden.jpg',
        'description' => 'Browse our Home & Garden collection'
    ],
    [
        'name' => 'Sports',
        'slug' => 'sports',
        'image' => 'images/categories/sports.jpg',
        'description' => 'Browse our Sports collection'
    ],
    [
        'name' => 'Books',
        'slug' => 'books',
        'image' => 'images/categories/books.jpg',
        'description' => 'Browse our Books collection'
    ],
];

foreach ($categories as $category) {
    Category::updateOrCreate(
        ['slug' => $category['slug']],
        [
            'name' => $category['name'],
            'slug' => $category['slug'],
            'description' => $category['description'],
            'image' => $category['image'],
            'is_active' => true
        ]
    );
}

        // Create Sample Products - Use local default image
        $productNames = [
            'Wireless Headphones', 'Smart Watch', 'Laptop Stand', 'USB-C Cable',
            'Phone Case', 'Bluetooth Speaker', 'Keyboard', 'Mouse Pad',
            'T-Shirt', 'Jeans', 'Running Shoes', 'Backpack'
        ];

        foreach ($productNames as $index => $name) {
            Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'High quality ' . $name . ' with amazing features',
                'price' => rand(10, 500),
                'sale_price' => rand(5, 400),
                'stock' => rand(10, 100),
                'image' => 'images/products/default.jpg', // Use local default image
                'category_id' => rand(1, 5),
                'is_featured' => $index < 4,
                'is_active' => true
            ]);
        }
    }
}