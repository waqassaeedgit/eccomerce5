<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)->withCount('orders');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->load(['orders' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $totalSpent = $customer->orders()->sum('total');
        $totalOrders = $customer->orders()->count();

        return view('admin.customers.show', compact('customer', 'totalSpent', 'totalOrders'));
    }
}