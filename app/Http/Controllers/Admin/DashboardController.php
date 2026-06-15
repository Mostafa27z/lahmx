<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $productsCount = Product::count();
        $categoriesCount = Category::count();
        $ordersCount = Order::count();
        
        // Total sales from successful/paid orders
        $totalSales = Order::where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        // Recent orders
        $recentOrders = Order::latest()->limit(5)->get();

        // Stock alert products (less than 5 units in stock)
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)->get();

        return view('admin.dashboard', compact(
            'productsCount',
            'categoriesCount',
            'ordersCount',
            'totalSales',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
