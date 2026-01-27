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
                'brand:id,name,slug'
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

    // GET /api/categories/{slug}/products
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

    // GET /api/brands/{slug}/products
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

     // GET /api/admin/products
    public function adminIndex()
    {
        $products = Product::with(['category', 'brand'])
            ->latest()
            ->paginate(15);

        return response()->json($products);
    }

    // POST /api/admin/products
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|unique:products,slug',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'required|exists:brands,id',
            'status'      => 'required|boolean'
        ]);

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created successfully',
            'data'    => $product
        ], 201);
    }

    // GET /api/admin/products/{id}
    public function showAdmin($id)
    {
        $product = Product::with(['variants.color', 'variants.size'])
            ->findOrFail($id);

        return response()->json($product);
    }

    // PUT /api/admin/products/{id}
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'required|exists:brands,id',
            'status'      => 'required|boolean'
        ]);

        $product->update($data);

        return response()->json([
            'message' => 'Product updated successfully',
            'data'    => $product
        ]);
    }

    // DELETE /api/admin/products/{id}
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}