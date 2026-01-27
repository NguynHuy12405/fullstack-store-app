<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Size;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý danh mục';

        $categories = Category::query()->filter($request->all())->paginate(10)->withQueryString();

        $counts = Category::selectRaw("
            count(*) as total_count,
            sum(case when status = 1 then 1 else 0 end) as active_count,
            sum(case when status = 0 then 1 else 0 end) as inactive_count
        ")->first();

        return view('admin.category.index', compact('categories', 'counts', 'viewData'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $category,
            ], 201);
        }
        return redirect()->back()->with('success', "Đã thêm danh mục: '{$category->name}'!");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return back()->with('success', "Đã cập nhật danh mục: '{$category->name}' ID: '{$category->id}'");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Category $category)
    {
        $category->delete();
        return back()->with('success', "Đã xóa danh mục '{$category->name}' ID: '{$category->id}'");
    }
    public function restore(Category $category)
    {
        $category->restore();
        return back()->with('success', "Khôi phục danh mục '{$category->name}' ID: '{$category->id}'");
    }
    public function forceDelete(Category $category)
    {
        $name = $category->name;
        $id = $category->id;
        try {
            $category->forceDelete();
        } catch (QueryException $error) {
            if ($error->getCode() == "23000") { //Kiểm tra khóa ngoại
                return back()->with('error', 'Dữ liệu này đang được sử dụng ở nơi khác, không thể xóa vĩnh viễn!');
            }
            return back()->with('error', 'Lỗi hệ thống: Không thể thực hiện lệnh xóa.');
        }
        return back()->with('success', "Đã xóa vĩnh viễn sản phẩm {$name} ID: {$id}");
    }
    public function trash(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý danh mục - Thùng rác';

        $categories = Category::query()->filter($request->all())->onlyTrashed()->paginate(15)->withQueryString();

        return view('admin.category.trash', compact('categories', 'viewData'));
    }

    public function getSizes(Request $request)
    {
        $sizes = Size::where('category_id', $request->id)->where('status', true)->get(['id', 'name']);
        if ($sizes->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Danh mục chưa có kích thước!',
                'data' => [],
            ], 404); // Trả về mảng rỗng và mã lỗi 404 (tùy chọn)
        }
        return response()->json($sizes);
    }
}
