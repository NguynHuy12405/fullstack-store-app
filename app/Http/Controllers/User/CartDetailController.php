<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartDetailRequest;
use App\Http\Requests\UpdateCartDetailRequest;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartDetailController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // 1. Sản phẩm được thêm vào Giỏ hàng
        $items = $user->cartDetails()
            ->with('variant.product.category')
            ->get();

        // 2. LOGIC GỢI Ý SẢN PHẨM MỚI
        // Lấy ID các sản phẩm đang có trong giỏ để loại trừ
        $cartProductIds = $items->pluck('variant.product_id')->unique()->toArray();
        
        // Lấy ID các danh mục của sản phẩm trong giỏ
        $categoryIds = $items->pluck('variant.product.category_id')->unique()->toArray();

        // Query sản phẩm gợi ý
        $query = Product::where('status', 1)
                    ->whereNotIn('id', $cartProductIds)
                    ->with('variants');

        if (!empty($categoryIds)) {
            // Nếu giỏ có hàng: Gợi ý sản phẩm cùng danh mục
            $query->whereIn('category_id', $categoryIds)->inRandomOrder();
        } else {
            // Nếu giỏ trống: Gợi ý sản phẩm Best Seller / Hot
            $query->orderByDesc('view');
        }

        // Lấy 4 sản phẩm
        $suggestedProducts = $query->take(4)->get();

        // Nếu số lượng gợi ý quá ít (ví dụ < 4), fill thêm bằng sản phẩm mới nhất
        if ($suggestedProducts->count() < 4) {
            $needed = 4 - $suggestedProducts->count();
            $moreProducts = Product::where('status', 1)
                ->whereNotIn('id', $cartProductIds)
                ->whereNotIn('id', $suggestedProducts->pluck('id'))
                ->latest()
                ->with('variants')
                ->take($needed)
                ->get();
            
            $suggestedProducts = $suggestedProducts->merge($moreProducts);
        }

        return view('user.cart.index', compact('items', 'suggestedProducts'));
    }

    // Hàm thêm sản phẩm vào giỏ
    public function store(StoreCartDetailRequest $request)
    {
        $user = Auth::user();

        // Kiểm tra xem biến thể này có tồn tại và còn hàng không
        $variant = Variant::find($request->variant_id);
        if (!$variant) {
            return back()->with('error', 'Sản phẩm không tồn tại.');
        }

        if ($variant->quantity < $request->quantity) {
            return back()->with('error', 'Sản phẩm chỉ còn ' . $variant->quantity . ' cái.');
        }

        // Kiểm tra sản phẩm đã có trong giỏ chưa
        $cartItem = CartDetail::where('user_id', $user->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($cartItem) {
            // Nếu có rồi thì cộng dồn số lượng
            $newQty = $cartItem->quantity + $request->quantity;
            
            // Đảm bảo không vượt quá kho
            $finalQty = min($newQty, $variant->quantity);
            
            $cartItem->update(['quantity' => $finalQty]);
        } else {
            // Nếu chưa có thì tạo mới
            CartDetail::create([
                'user_id'    => $user->id,
                'variant_id' => $request->variant_id,
                'quantity'   => $request->quantity,
                'status' => true,
            ]);
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    // Hàm cập nhật số lượng (Cho nút +/- trong trang giỏ hàng)
    public function update(UpdateCartDetailRequest $request, $id)
    {
        $cartDetail = CartDetail::findOrFail($id);
        abort_if($cartDetail->user_id !== Auth::id(), 403);

        $quantity = $request->input('quantity', $cartDetail->quantity);

        // 1. UPDATE VARIANT (COLOR / SIZE)
        if ($request->filled('variant_id')) {
            $newVariant = Variant::findOrFail($request->variant_id);

            // Check tồn kho
            if ($quantity > $newVariant->quantity) {
                return response()->json([
                    'error' => 'Biến thể này chỉ còn ' . $newVariant->quantity . ' sản phẩm'
                ], 400);
            }

            // Gộp nếu variant đã tồn tại
            $existing = CartDetail::where('user_id', Auth::id())
                ->where('variant_id', $newVariant->id)
                ->where('id', '!=', $cartDetail->id)
                ->first();

            if ($existing) {
                $existing->update([
                    'quantity' => min(
                        $existing->quantity + $quantity,
                        $newVariant->quantity
                    )
                ]);

                $cartDetail->delete();

                return response()->json([
                    'success' => true,
                    'merged'  => true,
                    'quantity'=> $existing->quantity,
                    'price'   => $newVariant->sale_price ?? $newVariant->price
                ]);
            }

            $cartDetail->variant_id = $newVariant->id;
        }

        // 2. UPDATE QUANTITY
        if ($request->filled('quantity')) {
            $variant = $cartDetail->variant;

            if ($quantity > $variant->quantity) {
                return response()->json([
                    'error' => 'Kho chỉ còn ' . $variant->quantity . ' sản phẩm'
                ], 400);
            }

            $cartDetail->quantity = $quantity;
        }

        // 3. UPDATE STATUS
        if ($request->has('status')) {
            $cartDetail->status = $request->boolean('status');
        }

        $cartDetail->save();

        return response()->json([
            'success'  => true,
            'quantity' => $cartDetail->quantity,
            'price'    => $cartDetail->variant->sale_price ?? $cartDetail->variant->price
        ]);
    }

    public function toggleStatus(Request $request, CartDetail $cartDetail)
    {
        abort_if($cartDetail->user_id !== Auth::id(), 403);

        $cartDetail->update([
            'status' => (bool) $request->boolean('status')
        ]);

        return response()->json(['success' => true]);
    }

    // Hàm xóa sản phẩm khỏi giỏ
    public function destroy($id)
    {
        $cartDetail = CartDetail::findOrFail($id);

        if ($cartDetail->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa sản phẩm này');
        }

        $cartDetail->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }

    public function clear()
    {
        CartDetail::where('user_id', Auth::id())->delete();
        return response()->json(['status' => 'success']);
    }
}