<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'orders'    => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'revenue'   => (float) Order::where('status', '!=', 'cancelled')->sum('total'),
            'products'  => Product::count(),
            'customers' => User::where('is_admin', false)->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        $lowStock = Product::where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(8)
            ->get();

        return view('admin.dashboard', [
            'stats'        => $stats,
            'recentOrders' => $recentOrders,
            'lowStock'     => $lowStock,
        ]);
    }
}