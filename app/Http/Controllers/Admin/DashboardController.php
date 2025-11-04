<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('is_admin', false)->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');

        // Recent orders
        $recentOrders = Order::with('user')
                            ->orderBy('created_at', 'desc')
                            ->take(10)
                            ->get();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
                              ->groupBy('status')
                              ->get();

        // Monthly revenue (last 6 months)
        $monthlyRevenue = Order::where('payment_status', 'paid')
                              ->where('created_at', '>=', now()->subMonths(6))
                              ->select(
                                  DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                                  DB::raw('SUM(total) as revenue')
                              )
                              ->groupBy('month')
                              ->orderBy('month')
                              ->get();

        // Low stock products
        $lowStockProducts = Product::where('stock', '<', 10)
                                  ->orderBy('stock')
                                  ->take(10)
                                  ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalRevenue',
            'recentOrders',
            'ordersByStatus',
            'monthlyRevenue',
            'lowStockProducts'
        ));
    }
}