<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSizeRequest;
use App\Http\Requests\UpdateSizeRequest;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý kích cỡ';

        $sizes = Size::query()->filter($request->all())->with('category')->paginate(10)->withQueryString();

        $categories = Category::all();

        $counts = Size::selectRaw("
            count(*) as total_count,
            sum(case when status = 1 then 1 else 0 end) as active_count,
            sum(case when status = 0 then 1 else 0 end) as inactive_count
        ")->first();

        return view('admin.size.index', compact('sizes', 'counts', 'viewData', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSizeRequest $request)
    {
        $size = Size::create($request->validated());
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $size,
            ], 201);
        }
        return redirect()->back()->with('success', "Đã thêm kích cỡ: '{$size->name}'!");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSizeRequest $request, Size $size)
    {
        $size->update($request->validated());
        return back()->with('success', "Đã cập nhật kích cỡ: '{$size->name}' ID: '{$size->id}'");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Size $size)
    {
        $size->delete();
        return back()->with('success', "Đã xóa kích cỡ '{$size->name}' ID: '{$size->id}'");
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Size $size)
    {
        $size->restore();
        return back()->with('success', "Khôi phục kích cỡ '{$size->name}' ID: '{$size->id}'");
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete(Size $size)
    {
        $name = $size->name;
        $id = $size->id;
        try {
            $size->forceDelete();
        } catch (QueryException $error) {
            if ($error->getCode() == "23000") { //Kiểm tra khóa ngoại
                return back()->with('error', 'Dữ liệu này đang được sử dụng ở nơi khác, không thể xóa vĩnh viễn!');
            }
            return back()->with('error', 'Lỗi hệ thống: Không thể thực hiện lệnh xóa.');
        }
        return back()->with('success', "Đã xóa vĩnh viễn kích cỡ {$name} ID: {$id}");
    }

    /**
     * Display trash of the resource.
     */
    public function trash(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý kích cỡ - Thùng rác';

        $sizes = Size::query()->filter($request->all())->onlyTrashed()->paginate(15)->withQueryString();

        $categories = Category::all();

        return view('admin.size.trash', compact('sizes', 'viewData', 'categories'));
    }
}
