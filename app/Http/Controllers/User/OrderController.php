<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng của user
     */
    public function index()
    {
        $viewData['title'] = 'Lịch sử đơn hàng - Fasion Shop';
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.profile.order.index', compact('orders', 'viewData'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.profile.order.show', compact('order'));
    }
}
