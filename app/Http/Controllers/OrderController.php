<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderStatus;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statusId = $request->get('status');

        $orders = Order::with(['orderStatus', 'shipStatus'])
            ->where('user_id', Auth::id())
            ->when($statusId, function ($query) use ($statusId) {
                $query->where('order_status_id', $statusId);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $statuses = OrderStatus::all();
        
        return view('user.profile.order.index', compact('orders', 'statuses', 'statusId'));
    }
    public function cancel(Order $order)
{
    // Chỉ cho hủy đơn của chính user
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }

    // Chỉ hủy khi đang chờ xác nhận
    if ($order->order_status_id != 1) {
        return back()->with('error', 'Đơn hàng này không thể hủy');
    }

    $order->update([
        'order_status_id' => 4, // Đã hủy
    ]);

    return back()->with('success', 'Đã hủy đơn hàng thành công');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
