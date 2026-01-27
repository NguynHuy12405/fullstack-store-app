<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\CartDetail;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail; 

class CheckoutController extends Controller
{
    public function index(){
    
        $user = Auth::user();

        // Lấy giỏ hàng + đầy đủ thông tin liên quan
        $cartItems = CartDetail::with([
                // Variant
                'variant',

                // Thông tin chi tiết của variant
                'variant.color',
                'variant.size',

                // Thông tin sản phẩm
                'variant.product',
                'variant.product.images',
            ])
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('user.cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống');
        }

        // Tính tổng tiền
        $total = $cartItems->sum(function ($item) {
            $variant = $item->variant;

            $price = ($variant->sale_price !== null && $variant->sale_price > 0)
                ? $variant->sale_price
                : $variant->price;

            return $price * $item->quantity;
        });

        
        return view('user.checkout.index', compact(
            'user',
            'cartItems',
            'total'
        ));
    }
    public function store(Request $request)
{
    $request->validate([
        'receiver_name' => 'required|string|max:255',
        'receiver_phone' => 'required|string|max:255',
        'receiver_address' => 'required|string|max:255',
    ]);

    $user = Auth::user();

    DB::transaction(function () use ($request, $user) {

        $cartItems = CartDetail::with('variant')
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->get();

        if ($cartItems->isEmpty()) {
            abort(403, 'Giỏ hàng trống');
        }

        $total = 0;

        foreach ($cartItems as $item) {
            $variant = $item->variant;

            if (!$variant || $variant->quantity < $item->quantity) {
                abort(403, 'Sản phẩm không đủ tồn kho');
            }

            $price = ($variant->sale_price && $variant->sale_price > 0)
                ? $variant->sale_price
                : $variant->price;

            $total += $price * $item->quantity;
        }

        //  tao order 
        $order = Order::create([
            'user_id' => $user->id,
            'name' => $request->receiver_name,
            'phone' => $request->receiver_phone,
            'address' => $request->receiver_address,
            'order_status_id' => 1, // chờ xác nhận
            'ship_status_id' => 1,  // chưa giao
            'total_price' => $total,
        ]);

        //  tao order detail
        foreach ($cartItems as $item) {
            $variant = $item->variant;

            OrderDetail::create([
                'order_id' => $order->id,
                'variant_id' => $variant->id,
                'price' => ($variant->sale_price && $variant->sale_price > 0)
                    ? $variant->sale_price
                    : $variant->price,
                'quantity' => $item->quantity,
            ]);

            $variant->decrement('quantity', $item->quantity);
            
        }
         CartDetail::where('user_id', $user->id)
                ->where('status', 1)
                ->delete();
    });

    return redirect()
        ->route('user.home.index')
        ->with('success', 'Đặt hàng thành công!');
}
}
