<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with([
                'variants:id,product_id,price',
                'category:id,name,slug',
                'brand:id,name',
            ])
            ->filter($request->only([
                'search',
                'category',
                'brand',
                'tags',
                'price_from',
                'price_to',
            ]))
            ->where('products.status', true)
            ->select('products.*');

        match ($request->sort) {
            'price_asc' => $products
                ->leftJoin('variants', 'variants.product_id', '=', 'products.id')
                ->orderBy(DB::raw('MIN(variants.price)'), 'asc')
                ->groupBy('products.id'),

            'price_desc' => $products
                ->leftJoin('variants', 'variants.product_id', '=', 'products.id')
                ->orderBy(DB::raw('MAX(variants.price)'), 'desc')
                ->groupBy('products.id'),

            default => $products->latest(),
        };

        $ratingCounts = Product::query()
            ->where('status', 1)
            ->selectRaw('FLOOR(rating) as star, COUNT(*) as total')
            ->groupBy('star')
            ->pluck('total', 'star');

        $products = $products->paginate(12)->withQueryString();
        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();

        return view('user.product.index', compact('products', 'categories', 'brands', 'ratingCounts'));
    }

    // Hiển thị chi tiết sản phẩm
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->with([
                'variants.color',
                'variants.size',
                'reviews.user'
            ])
            ->withCount([
                'wishlists as wishlist_count' => function ($q) {
                    $q->whereNull('variant_id');
                }
            ])
            ->firstOrFail();

        // Lấy Color duy nhất & active
        $colors = $product->variants
            ->pluck('color')
            ->filter(fn($c) => $c && $c->status == 1)
            ->unique('id')
            ->values();

        // Lấy Size duy nhất & active
        $sizes = $product->variants
            ->pluck('size')
            ->filter(fn($s) => $s && $s->status == 1)
            ->unique('id')
            ->values();

        // Tăng lượt xem
        $product->increment('view');

        // Sản phẩm liên quan
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('status', 1)
            ->with('variants')
            ->orderByDesc('view')
            ->take(4)
            ->get();

        // Reviews
        $reviews = $product->reviews()
            ->orderByDesc('created_at')
            ->paginate(5);

        $totalRating = $reviews->total();

        // Phân bố sao
        $starCounts = $product->reviewsRaw()
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        $ratingDist = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDist[$i] = $starCounts[$i] ?? 0;
        }

        $averageRating = $totalRating > 0
            ? round($product->reviews()->avg('rating'), 1)
            : 0;

        return view('user.product.show', compact(
            'product',
            'relatedProducts',
            'colors',
            'sizes',
            'reviews',
            'ratingDist',
            'starCounts',
            'averageRating',
            'totalRating'
        ));
    }
}
