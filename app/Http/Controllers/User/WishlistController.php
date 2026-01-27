<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToggleWishlistRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Lấy danh sách hiển thị ra view
        $wishlists = $user->wishlists()->with(['product', 'variant'])->get();
        return view('user.profile.wishlist.index', compact('wishlists'));
    }    

    public function toggle(ToggleWishlistRequest $request)
    {
        // 1. Check Login
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập.'], 401);
        }

        $userId    = Auth::id();
        $productId = $request->product_id;
        $variantId = $request->input('variant_id');

        // 3. Query tìm kiếm
        $wishlist = Wishlist::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->where('variant_id', $variantId)
                            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json([
                'status' => 'success',
                'action' => 'removed',
                'message' => 'Đã xóa khỏi yêu thích'
            ]);
        }

        Wishlist::create([
            'user_id'    => $userId,
            'product_id' => $productId,
            'variant_id' => $variantId
        ]);

        return response()->json([
            'status' => 'success',
            'action' => 'added',
            'message' => 'Đã thêm vào yêu thích'
        ]);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('id', $id)->where('user_id', Auth::id())->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'success', 'message' => 'Đã xóa sản phẩm']);
        }

        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm'], 404);
    }

    public function clear()
    {
        Wishlist::where('user_id', Auth::id())->delete();
        return response()->json(['status' => 'success', 'message' => 'Đã xóa toàn bộ danh sách']);
    }
}
