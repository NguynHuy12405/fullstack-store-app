<?php

namespace App\Http\Controllers\Admin;

use App\Models\Color;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreColorRequest;
use App\Http\Requests\UpdateColorRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý màu sắc';

        $colors = Color::query()->filter($request->all())->paginate(10)->withQueryString();

        $counts = Color::selectRaw("
            count(*) as total_count,
            sum(case when status = 1 then 1 else 0 end) as active_count,
            sum(case when status = 0 then 1 else 0 end) as inactive_count
        ")->first();

        return view('admin.color.index', compact('colors', 'counts', 'viewData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreColorRequest $request)
    {
        $color = Color::create($request->validated());
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $color,
            ], 201);
        }
        return redirect()->back()->with('success', "Đã thêm màu sắc: '{$color->name}'!");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColorRequest $request, Color $color)
    {
        $color->update($request->validated());
        return back()->with('success', "Đã cập nhật màu sắc: '{$color->name}' ID: '{$color->id}'");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Color $color)
    {
        $color->delete();
        return back()->with('success', "Đã xóa màu sắc '{$color->name}' ID: '{$color->id}'");
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Color $color)
    {
        $color->restore();
        return back()->with('success', "Khôi phục màu sắc '{$color->name}' ID: '{$color->id}'");
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete(Color $color)
    {
        $name = $color->name;
        $id = $color->id;
        try {
            $color->forceDelete();
        } catch (QueryException $error) {
            if ($error->getCode() == "23000") { //Kiểm tra khóa ngoại
                return back()->with('error', 'Dữ liệu này đang được sử dụng ở nơi khác, không thể xóa vĩnh viễn!');
            }
            return back()->with('error', 'Lỗi hệ thống: Không thể thực hiện lệnh xóa.');
        }
        return back()->with('success', "Đã xóa vĩnh viễn màu sắc {$name} ID: {$id}");
    }

    /**
     * Display trash of the resource.
     */
    public function trash(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý màu sắc - Thùng rác';

        $colors = Color::query()->filter($request->all())->onlyTrashed()->paginate(15)->withQueryString();

        return view('admin.color.trash', compact('colors', 'viewData'));
    }
}
