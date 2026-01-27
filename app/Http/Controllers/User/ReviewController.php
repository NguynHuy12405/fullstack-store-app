<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'user', 'replierUser.role'])
            ->where('status', 1)
            ->where('user_id', Auth::id())
            ->whereNotNull('reply')
            ->latest()
            ->get();

        return view('user.review.index', compact('reviews'));
    }

    public function store(StoreReviewRequest $request, $productId)
    {
        $userId = Auth::id();

        // Kiểm tra đã mua + đã nhận hàng (status 4)
        $orderExists = Order::where([
                'user_id' => $userId,
                'order_status_id' => 4,
            ])
            ->whereHas('orderDetails.variant', fn($v) => $v->where('product_id', $productId))
            ->exists();

        if (!$orderExists) {
            return response()->json([
                'status' => 'not_received',
                'message' => 'Bạn cần mua và nhận sản phẩm này để đánh giá.'
            ], 400);
        }

        // Kiểm tra đã review chưa
        $reviewExists = Review::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        if ($reviewExists) {
            return response()->json([
                'status' => 'exists',
                'message' => 'Bạn đã đánh giá sản phẩm này rồi.'
            ], 409);
        }

        // Lưu review mới
        Review::create([
            'product_id' => $productId,
            'user_id'    => $userId,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        // Cập nhật rating trung bình
        $newRating = Review::where('product_id', $productId)->avg('rating');
        Product::where('id', $productId)->update([
            'rating' => round($newRating, 1)
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Đánh giá của bạn đã được gửi!'
        ], 200);
    }
}
