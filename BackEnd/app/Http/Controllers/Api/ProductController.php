<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('status', 1)
            ->with([
                'category:id,name,slug',
                'brand:id,name,slug',
            ])
            ->withMin(['variants as min_price' => function ($q) {
                $q->where('status', 1);
            }], 'price');

        // 🔍 Filter theo category slug
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 🔍 Filter theo brand slug
        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // 💰 Filter giá
        if ($request->filled('price_min')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereRaw('COALESCE(sale_price, price) >= ?', [$request->price_min]);
            });
        }

        if ($request->filled('price_max')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereRaw('COALESCE(sale_price, price) <= ?', [$request->price_max]);
            });
        }

        // 🔃 Sort
        if ($request->sort === 'latest') {
            $query->orderByDesc('id');
        } elseif ($request->sort === 'popular') {
            $query->orderByDesc('view');
        }

        $products = $query->paginate(12);

        return ProductResource::collection($products);
    }

    // Hiển thị chi tiết sản phẩm
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->with([
                'category:id,name,slug',
                'brand:id,name,slug',
                'variants' => function ($q) {
                    $q->where('status', 1)
                      ->with([
                        'color:id,name,hex_code',
                        'size:id,name'
                      ]);
                }
            ])
            ->firstOrFail();

        // 👀 tăng view
        $product->increment('view');

        return new ProductDetailResource($product);
    }

    // GET /api/categories/{slug}
    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::where('category_id', $category->id)
            ->where('status', 1)
            ->with(['category:id,name,slug', 'brand:id,name,slug'])
            ->withMin('variants', 'price')
            ->paginate(12);

        return ProductResource::collection($products);
    }

    // GET /api/brands/{slug}
    public function byBrand($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $products = Product::where('brand_id', $brand->id)
            ->where('status', 1)
            ->with(['category:id,name,slug', 'brand:id,name,slug'])
            ->withMin('variants', 'price')
            ->paginate(12);

        return ProductResource::collection($products);
    }
}