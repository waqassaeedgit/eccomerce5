<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\TopBar;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $topBars = TopBar::where('is_active', true)->get();
        $sliders = Slider::where('is_active', true)->orderBy('order')->get();
        $categories = Category::where('is_active', true)->withCount('products')->get();
        $featuredProducts = Product::where('is_featured', true)
                                   ->where('is_active', true)
                                   ->take(8)
                                   ->get();

        return view('home', compact('topBars', 'sliders', 'categories', 'featuredProducts'));
    }
}