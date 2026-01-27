<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Traits\ImageTrait;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Tag;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý sản phẩm';

        $products = Product::query()->filter($request->all())->with(['variants.color', 'variants.size', 'tags', 'images'])->Paginate(10)->withQueryString();

        $brands = Brand::all();

        $categories = Category::all();

        $tags = Tag::all();

        $colors = Color::all();

        $sizes = Size::all();

        $counts = Product::selectRaw("
            count(*) as total_count,
            sum(case when status = 1 then 1 else 0 end) as active_count,
            sum(case when status = 0 then 1 else 0 end) as inactive_count
        ")->first();

        return view('admin.product.index', compact('products', 'viewData', 'brands', 'categories', 'colors', 'tags', 'sizes', 'counts'));
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
    public function store(StoreProductRequest $request)
    {
        $imgMsg = "";
        $product = Product::create($request->validated());
        $baseName =  $product->id . "_" . $product->slug;
        if ($request->hasFile('cropped-thumbnail')) {
            $thumbnail = $baseName . "." . $request->file('cropped-thumbnail')->extension();
            $thumbPath = $this->uploadImage($thumbnail, $request->file('cropped-thumbnail'), 'product/thumbnail');
            if($thumbPath === null){
                return redirect()->back()->with('error', 'Lỗi tải ảnh lên. Vui lòng thử lại!');
            }
            $product->update(['thumbnail' => $thumbPath]);
            $imgMsg = $imgMsg . " Ảnh chính: 1 hình";
        } else {
            $imgMsg = $imgMsg . " Ảnh chính rỗng";
        }
        if ($request->hasFile('cropped-images')) {
            foreach ($request->file('cropped-images') as $index => $file) {
                $imagesName = $baseName . "_" . ($index + 1) . "." . $file->extension();
                $imagePath = $this->uploadImage($imagesName, $file, 'product/image');
                if ($imagePath === null) {
                    return redirect()->back()->with('error', 'Lỗi tải ảnh lên. Vui lòng thử lại!');
                }
                $product->images()->create(['url' => $imagePath]);
            }
            $imgMsg = $imgMsg . ", Ảnh phụ: " . count($request->file('cropped-images')) . " hình";
        } else {
            $imgMsg = $imgMsg . ", ảnh phụ rỗng";
        }
        $product->variants()->createMany($request->variants);
        $product->tags()->sync($request->tags);
        return redirect()->back()->with('success', 'Đã thêm sản phẩm "' . $product->name . '" cùng các biến thể!' . $imgMsg);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $imgMsg = "";
        $product->update($request->validated());
        $baseName =  $product->id . "_" . $product->slug;
        if ($request->hasFile('cropped-thumbnail')) {
            $this->deleteImage($product->thumbnail);
            $thumbnail = $baseName . "." . $request->file('cropped-thumbnail')->extension();
            $thumbPath = $this->uploadImage($thumbnail, $request->file('cropped-thumbnail'), 'product/thumbnail');
            if($thumbPath === null){
                return redirect()->back()->with('error', 'Lỗi tải ảnh lên. Vui lòng thử lại!');
            }
            $product->update(['thumbnail' => $thumbPath]);
            $imgMsg = $imgMsg . " Cập nhật ảnh chính";
        } else {
            $imgMsg = $imgMsg . ", Ảnh chính giữ nguyên";
        }
        if ($request->hasFile('cropped-images')) {
            foreach ($request->file('cropped-images') as $file) {
                $imgNum = $file->getClientOriginalName();
                $oldImage = $product->images()->where('url', 'like', "%_{$imgNum}")->value('url');
                $imagesName = $baseName . "_" . $imgNum;
                $this->deleteImage($oldImage);
                $imagePath = $this->uploadImage($imagesName, $file, 'product/image');
                if ($imagePath === null) {
                    return redirect()->back()->with('error', 'Lỗi tải ảnh lên. Vui lòng thử lại!');
                }
                if ($oldImage) {
                    $product->images()->where('url', $oldImage)->update(['url' => $imagePath]);
                } else {
                    $product->images()->create(['url' => $imagePath]);
                }
            }
            $imgMsg = $imgMsg . ", Ảnh phụ: " . count($request->file('cropped-images')) . " hình";
        } else {
            $imgMsg = $imgMsg . ", ảnh phụ giữ nguyên";
        }
        foreach ($request->variants as $variant) {
            $cleanDate = array_diff_key($variant, array_flip(['id', 'size_id', 'color_id', 'product_id', 'created_at', 'updated_at', 'deleted_at', 'color', 'size']));
            $product->variants()->where('id', $variant['id'])->update($cleanDate);
        }
        $product->tags()->sync($request->tags);
        return redirect()->back()->with('success', "Đã sửa sản phẩm: '{$product->name}'$imgMsg");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Product $product)
    {
        $product->delete();
        return back()->with('success', "Đã xóa sản phẩm '{$product->name}' ID: '{$product->id}'");
    }
    public function restore(Product $product)
    {
        $product->restore();
        return back()->with('success', "Khôi phục sản phẩm '{$product->name}' ID: '{$product->id}'");
    }
    public function forceDelete(Product $product)
    {
        $name = $product->name;
        $id = $product->id;
        $thumbnail = $product->thumbnail;
        $images = $product->images->pluck('url')->all();
        try {
            $product->forceDelete();
        } catch (QueryException $error) {
            if ($error->getCode() == "23000") { //Kiểm tra khóa ngoại
                return back()->with('error', 'Dữ liệu này đang được sử dụng ở nơi khác, không thể xóa vĩnh viễn!');
            }
            return back()->with('error', 'Lỗi hệ thống: Không thể thực hiện lệnh xóa.');
        }
        $this->deleteImage($thumbnail);
        foreach ($images as $img) {
            $this->deleteImage($img);
        }
        return back()->with('success', "Đã xóa vĩnh viễn sản phẩm {$name} ID: {$id}");
    }
    public function trash(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Thùng rác sản phẩm';

        $products = Product::query()->filter($request->all())->onlyTrashed()->with('variants.color', 'variants.size')->paginate(15)->withQueryString();

        $brands = Brand::all();

        $categories = Category::all();

        $tags = Tag::all();

        return view('admin.product.trash', compact('products', 'viewData', 'brands', 'tags', 'categories'));
    }
}
