<?php

namespace App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    // GET /api/admin/products
    public function index()
    {
        $products = Product::with(['category', 'brand'])
            ->latest()
            ->paginate(15);

        return response()->json($products);
    }

    // POST /api/admin/products
    public function store(StoreProductRequest $request)
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
    public function edit($id)
    {
        $product = Product::with(['variants.color', 'variants.size'])
            ->findOrFail($id);

        return response()->json($product);
    }

    // PUT /api/admin/products/{id}
    public function update(StoreProductRequest $request, $id)
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