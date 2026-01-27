<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $months = [];
        $monthlyRevenueData = [];
        $dailyLabels = [];
        $dailyRevenueData = [];
        $daysInMonth = now()->daysInMonth;
        $startDate = Carbon::now()->subMonth();

        $totalRevenue = Order::where('order_status_id', 4)
            ->where('ship_status_id', 3)
            ->where('created_at', '>=', $startDate)
            ->sum('total_price');

        $revenueChart = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('order_status_id', 4)
            ->where('ship_status_id', 3)
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $completedOrders = Order::where('order_status_id', 4)
            ->where('ship_status_id', 3)
            ->where('created_at', '>=', $startDate)
            ->count();

        $unprocessedOrders = Order::where('order_status_id', 1)->count();

        $totalCustomers = User::count();
        $lowStock = Variant::where('quantity', '<=', 10)->count();

        $revenueByMonth = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->whereYear('created_at', now()->year)
            ->where('order_status_id', 4)
            ->where('ship_status_id', 3)
            ->groupBy('month')
            ->pluck('total', 'month');
        
        for ($m = 1; $m <= 12; $m++) {
            $months[] = 'T' . $m;
            $monthlyRevenueData[] = round(($revenueByMonth[$m] ?? 0) / 1_000_000, 2); // triệu VNĐ
        }

        $revenueByDay = Order::selectRaw('DAY(created_at) as day, SUM(total_price) as total')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('order_status_id', 4)
            ->where('ship_status_id', 3)
            ->groupBy('day')
            ->pluck('total', 'day');

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dailyLabels[] = $d;
            $dailyRevenueData[] = round(($revenueByDay[$d] ?? 0) / 1_000_000, 2);
        }

        $recentOrders = Order::with([
            'orderDetails.variant.product',
            'orderStatus',
            'user'
        ])
        ->latest()
        ->limit(3)
        ->get();

        $topProducts = Product::query()
            ->select(
                'products.id',
                'products.name',
                'products.thumbnail',
                DB::raw('SUM(order_details.quantity) as sold_quantity')
            )
            ->join('variants', 'variants.product_id', '=', 'products.id')
            ->join('order_details', 'order_details.variant_id', '=', 'variants.id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.order_status_id', 4)
            ->groupBy('products.id', 'products.name', 'products.thumbnail')
            ->orderByDesc('sold_quantity')
            ->limit(5)
            ->get();

        return view('admin.home.index', compact(
            'totalRevenue', 
            'lowStock', 
            'totalCustomers', 
            'completedOrders',
            'unprocessedOrders',
            'months',
            'monthlyRevenueData',
            'dailyLabels',
            'dailyRevenueData',
            'recentOrders',
            'topProducts'
        ));  
    }
}
