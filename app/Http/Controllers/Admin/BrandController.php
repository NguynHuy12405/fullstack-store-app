<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý thương hiệu';

        $brands = Brand::query()->filter($request->all())->Paginate(10)->withQueryString();

        $counts = Brand::selectRaw("
            count(*) as total_count,
            sum(case when status = 1 then 1 else 0 end) as active_count,
            sum(case when status = 0 then 1 else 0 end) as inactive_count
        ")->first();

        return view('admin.brand.index', compact('brands', 'viewData', 'counts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create($request->validated());
        if ($request->hasFile('image')) {
            $imgName = "{$brand->id}_{$brand->slug}." . $request->file('image')->extension();
            $imgPath = $this->uploadImage($imgName, $request->file('image'), 'brand');
            $brand->update(['image' => $imgPath]);
        }
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $brand,
            ], 201);
        }
        return redirect()->back()->with('success', "Đã thêm thương hiệu: '{$brand->name}'!");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $this->deleteImage($brand->getOriginal('image'));
            $imgName = "{$brand->id}_{$brand->slug}." . $request->file('image')->extension();
            $data['image'] = $this->uploadImage($imgName, $request->file('image'), 'brand');
        }
        $brand->update($data);
        return back()->with('success', "Đã cập nhật thương hiệu: '{$brand->name}' ID: '{$brand->id}'");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Brand $brand)
    {
        $brand->delete();
        return back()->with('success', "Đã xóa thương hiệu '{$brand->name}' ID: '{$brand->id}'");
    }

    public function restore(Brand $brand)
    {
        $brand->restore();
        return back()->with('success', "Khôi phục thương hiệu '{$brand->name}' ID: '{$brand->id}'");
    }

    public function forceDelete(Brand $brand)
    {
        $name = $brand->name;
        $id = $brand->id;
        try {
            $brand->forceDelete();
        } catch (\Illuminate\Database\QueryException $error) {
            if ($error->getCode() == "23000") {
                return back()->with('error', 'Dữ liệu này đang được sử dụng ở nơi khác, không thể xóa vĩnh viễn!');
            }
            return back()->with('error', 'Lỗi hệ thống: Không thể thực hiện lệnh xóa.');
        }
        return back()->with('success', "Đã xóa vĩnh viễn thương hiệu {$name} ID: {$id}");
    }

    public function trash(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý thương hiệu - Thùng rác';

        $brands = Brand::query()->filter($request->all())->onlyTrashed()->paginate(15)->withQueryString();
        return view('admin.brand.trash', compact('brands', 'viewData'));
    }
}
