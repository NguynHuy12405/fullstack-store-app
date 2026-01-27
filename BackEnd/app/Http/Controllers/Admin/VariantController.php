<?php

namespace App\Http\Controllers\Admin;

use App\Models\Variant;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateVariantRequest;
use Illuminate\Database\QueryException;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreVariantRequest $request)
    {
        $variant = Variant::create($request->validated());
        return back()->with('success', "Đã thêm biến thể cho '{$variant->product->name}!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Variant $variant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Variant $variant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVariantRequest $request, Variant $variant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Variant $variant)
    {
        if($variant->product->variants->count() == 1){
            return back()->with('error', 'Bạn không thể xóa biến thể cuối cùng');
        }
        try {
            $variant->delete();
        } catch (QueryException $error) {
            if ($error->getCode() == "23000") { //Kiểm tra khóa ngoại
                return back()->with('error', 'Dữ liệu này đang được sử dụng ở nơi khác, không thể xóa vĩnh viễn!');
            }
            return back()->with('error', 'Lỗi hệ thống: Không thể thực hiện lệnh xóa.');
        }
        return back()->with('success', 'Đã xóa biến thể');
    }
}
