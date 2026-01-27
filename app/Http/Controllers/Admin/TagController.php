<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý nhãn';

        $tags = Tag::query()->filter($request->all())->paginate(10)->withQueryString();

        $counts = Tag::selectRaw("
            count(*) as total_count,
            sum(case when status = 1 then 1 else 0 end) as active_count,
            sum(case when status = 0 then 1 else 0 end) as inactive_count
        ")->first();

        return view('admin.tag.index', compact('tags', 'counts', 'viewData'));
    }

    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create($request->validated());
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $tag,
            ], 201);
        }
        return redirect()->back()->with('success', "Đã thêm nhãn: '{$tag->name}'!");
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());
        return back()->with('success', "Đã cập nhật nhãn: '{$tag->name}' ID: '{$tag->id}'");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Tag $tag)
    {
        $tag->delete();
        return back()->with('success', "Đã xóa nhãn '{$tag->name}' ID: '{$tag->id}'");
    }
    public function restore(Tag $tag)
    {
        $tag->restore();
        return back()->with('success', "Khôi phục nhãn '{$tag->name}' ID: '{$tag->id}'");
    }
    public function forceDelete(Tag $tag)
    {
        $name = $tag->name;
        $id = $tag->id;
        try {
            $tag->forceDelete();
        } catch (QueryException $error) {
            if ($error->getCode() == "23000") { //Kiểm tra khóa ngoại
                return back()->with('error', 'Dữ liệu này đang được sử dụng ở nơi khác, không thể xóa vĩnh viễn!');
            }
            return back()->with('error', 'Lỗi hệ thống: Không thể thực hiện lệnh xóa.');
        }
        return back()->with('success', "Đã xóa vĩnh viễn nhãn {$name} ID: {$id}");
    }
    public function trash(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Sản phẩm';
        $viewData['subtitle'] = 'Quản lý nhãn - Thùng rác';

        $tags = Tag::query()->filter($request->all())->onlyTrashed()->paginate(15)->withQueryString();

        return view('admin.tag.trash', compact('tags', 'viewData'));
    }
}
