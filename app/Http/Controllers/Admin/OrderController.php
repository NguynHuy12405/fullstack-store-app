<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Đơn hàng';
        $viewData['subtitle'] = 'Quản lý đơn hàng';

        $orders = Order::query()->filter($request->all())->with(['user.role', 'orderDetails.variant.product', 'shipStatus', 'orderStatus'])->paginate(10)->withQueryString();

        $counts = Order::selectRaw("
            count(*) as total_count,
            (select sum(total_price) from orders where order_status_id = 3) as total_price_count,
            (select sum(order_details.quantity) from order_details join orders on orders.id = order_details.order_id where orders.order_status_id = 3) as total_items_count
        ")->first();

        return view('admin.order.index', compact('viewData', 'orders', 'counts'));
    }

    public function confirm(Order $order)
    {
        if ($order->order_status_id != 1) {
            return back()->with('error', "Đơn #{$order->id} đã được xử lý trước đó");
        }
        $order->update(['admin_id' => Auth::id(), 'order_status_id' => 2]);
        return back()->with('success', 'Đơn hàng đã được xác nhận.');
    }

    public function decline(Order $order, Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        if ($order->order_status_id != 1) {
            return back()->with('error', "Đơn #{$order->id} đã được xử lý trước đó");
        }
        $order->update(['admin_id' => Auth::id(), 'order_status_id' => 4, 'message' => $request->message]);
        return back()->with('success', 'Đơn hàng đã bị từ chối.');
    }

    public function accept(Order $order)
    {
        if ($order->ship_status_id != 1) {
            return back()->with('error', "Đơn #{$order->id} đã được nhận trước đó");
        }
        $order->update(['shipper_id' => Auth::id(), 'ship_status_id' => 2]);
        return back()->with('success', "Nhận đơn #{$order->id} thành công");
    }

    public function shipped(Order $order)
    {
        $order->update(['ship_status_id' => 3, 'order_status_id' => 3]);
        return back()->with('success', "Đã giao đơn #{$order->id} thành công");
    }

    public function fail(Request $request, Order $order)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        $order->update(['ship_status_id' => 4, 'message' => $request->message]);
        return back()->with('success', "Đã báo cáo đơn hàng #{$order->id}");
    }

    public function ship(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Đơn hàng';
        $viewData['subtitle'] = 'Nhận giao hàng';

        $orders = Order::query()->filter($request->all())->where([['order_status_id', 2], ['ship_status_id', 1]])->with(['user', 'orderDetails.variant.product'])->paginate(10)->withQueryString();

        return view('admin.order.ship', compact('viewData', 'orders'));
    }

    public function accepted(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Đơn hàng';
        $viewData['subtitle'] = 'Nhận giao hàng';

        $counts = Order::where('shipper_id', Auth::id())
        ->selectRaw("
            count(*) as total_count,
            sum(case when order_status_id = 2 then 1 else 0 end) as total_accepted_count,
            sum(case when order_status_id = 3 then 1 else 0 end) as total_shipped_count
        ")->first();

        if (!$request->has('ship_status')) {
            $request->merge(['ship_status' => 2]);
        }

        $orders = Order::query()->filter($request->all())->where([['order_status_id', '>', 1], ['shipper_id', Auth::id()]])->with(['user', 'orderDetails.variant.product'])->paginate(10)->withQueryString();

        return view('admin.order.accepted', compact('viewData', 'orders', 'counts'));
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with([
            'orderDetails.variant.product',
            'orderDetails.variant.size',
            'orderDetails.variant.color',
            'orderStatus',
            'user'
        ])->findOrFail($id);

        return response()->json($order);
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
        $data = $request->validated();
        if($data['ship_status_id'] == 1){
            $data['shipper_id'] = null;
        }
        $order->update($data);
        return back()->with('success', "Cập nhật đơn hàng #{$order->id} thành công");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
